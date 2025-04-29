<?php
namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;
use App\QueueStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CounterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->user()->counter_id) {
            return redirect('counter/' . request()->user()->counter_id);
        }

        $selectedDateInput = request('selectedDate', now());

        if (! \Carbon\Carbon::hasFormat($selectedDateInput, 'Y-m-d')) {
            return redirect()->route('counter.panel', [
                'selectedDate' => now()->format('Y-m-d'),
            ]);
        }

        $selectedDate = \Carbon\Carbon::parse($selectedDateInput);

        $services = Service::with(['counters' => function ($query) {
            $query->orderBy('name');
        }])->get();

        $counters = Counter::with([
            'service:id,name',
            'user:id,name',
            'lastQueueCalled' => function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate);
            },
        ])->withCount([
            'queues as queues_count'         => function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate);
            },
            'queues as queues_skipped_count' => function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate)->where('status', 'skipped');
            },
            'queues as queues_done_count'    => function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate)->where('status', 'done');
            },
        ])->get();

        return Inertia::render('Counter/ServiceCounter', compact('services', 'counters', 'selectedDate'));
    }

    public function callNext(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'counter_id' => 'required|exists:counters,id',
        ]);

        $nextQueue = Queue::where('service_id', $request->service_id)
            ->where('status', 'waiting')
            ->orderBy('created_at')
            ->first();

        if (! $nextQueue) {
            return back()->withErrors(['Tidak ada antrean lagi']);
        }

        $nextQueue->update([
            'status'     => 'calling',
            'called_at'  => now(),
            'counter_id' => $request->counter_id,
        ]);

        return back()->with('success', "Memanggil antrean: {$nextQueue->display_number}");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'ip_address' => 'nullable|ip',
        ]);

        Counter::create($request->only('name', 'service_id', 'ip_address'));

        return back()->with('success', 'Counter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Counter $counter)
    {
        $status = request('status', 'waiting');

        if (request()->user()->counter_id && request()->user()->counter_id != $counter->id) {
            abort(401, 'Unauthorized');
        }

        if (request()->user()->counter_id && request()->user()->counter_id == $counter->id) {
            $counter->user_id = request()->user()->id;
            $counter->save();
        }

        $statuses = collect(QueueStatus::cases())
            ->map(fn($status) => ['value' => $status->value, 'label' => ucfirst($status->name)]);

        $counter->load(['service:id,name', 'user:id,name']);

        $calledQueue = Queue::where('service_id', $counter->service_id)
            ->where('counter_id', $counter->id)
            ->where('status', 'calling')
            ->whereDate('updated_at', today())
            ->orderBy('updated_at')
            ->first();

        $queues = Queue::where('service_id', $counter->service_id)
            ->with(['service:id,name', 'counter:id,name'])
            ->where('status', $status) // Default to 'waiting' if no status is provided
            ->whereDate('created_at', today())
            ->orderBy('created_at')
            ->get();

        return Inertia::render('Counter/CounterView', compact('counter', 'queues', 'calledQueue', 'statuses','status'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Counter $counter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Counter $counter)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'ip_address' => 'nullable|ip',
        ]);

        $counter->update($request->only('name', 'service_id', 'ip_address'));

        return back()->with('success', 'Counter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Counter $counter)
    {

        // Check if the counter has any queues
        if ($counter->queues()->exists()) {
            return back()->withErrors(['Counter cannot be deleted because it has queues']);
        }

        // Check if the counter has any users
        if ($counter->user()->exists()) {
            return back()->withErrors(['Counter cannot be deleted because it has users']);
        }

        $counter->delete();

        return back()->with('success', 'Counter deleted successfully.');
    }
}

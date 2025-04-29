<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreQueueRequest;
use App\Http\Requests\UpdateQueueRequest;
use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;
use App\QueueStatus;
use App\Services\QueueService;
use Inertia\Inertia;

class QueueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status     = request('status');
        $service_id = request('service_id');
        $counter_id = request('counter_id');

        $selectedDateInput = request('selectedDate', now());

        if (! \Carbon\Carbon::hasFormat($selectedDateInput, 'Y-m-d')) {
            return redirect()->route('queue', [
                'selectedDate' => now()->format('Y-m-d'),
            ]);
        }

        $selectedDate = \Carbon\Carbon::parse($selectedDateInput);

        $services = Service::all();

        $counters = Counter::all();

        $queueStatuses = collect(QueueStatus::cases())
            ->map(fn($status) => ['value' => $status->value, 'label' => ucfirst($status->name)]);

        $queues = Queue::with(['service', 'counter'])
            ->whereDate('created_at', $selectedDateInput)
            ->when($service_id, function ($query) use ($service_id) {
                $query->where('service_id', $service_id);
            })
            ->when($counter_id, function ($query) use ($counter_id) {
                $query->where('counter_id', $counter_id);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('id')->get();

        return Inertia::render('Queue/Index', compact('queues', 'services', 'counters', 'queueStatuses', 'selectedDate', 'status', 'service_id', 'counter_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();

        return Inertia::render('Queue/CreateQueue', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQueueRequest $request, QueueService $queueService)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        $service = Service::findOrFail($request->service_id);

        $queue = $queueService->createQueue($service);

        return Inertia::render('Queue/Ticket', [
            'queue' => $queue->load('service'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Queue $queue)
    {
        $queue->load(['service', 'counter']);

        return response()->json($queue);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Queue $queue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQueueRequest $request, Queue $queue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Queue $queue)
    {
        //
    }

    public function callNext($counterId, QueueService $queueService)
    {
        $counter = Counter::findOrFail($counterId);
        $queue   = $queueService->callNextQueue($counter, today());

        if ($queue) {
            return back()->with('success', "Memanggil antrean: {$queue->number}");
        } else {
            return back()->with('message', "Tidak ada antrian");
        }
    }

    public function finish($queueId, QueueService $queueService)
    {
        $queue = Queue::findOrFail($queueId);
        $queueService->finishQueue($queue);

        return back()->with('success', "Nomor antrean: {$queue->number} Selesai");
    }

    public function skip($queueId, QueueService $queueService)
    {
        $queue = Queue::findOrFail($queueId);

        $queueService->skipQueue($queue);

        return back()->with('success', "Antrean dilewati & Memanggil antrean: {$queue->number}");
    }
}

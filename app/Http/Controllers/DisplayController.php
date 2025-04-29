<?php
namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;
use Inertia\Inertia;

class DisplayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $selectedDate = today();

        $services = Service::with(['counters'])->get();

        $counters = Counter::with(['service',
            'lastQueueCalled' => function ($query) use ($selectedDate) {
                $query->whereDate('called_at', $selectedDate);
            },
        ])->where('is_active', true)->get();

        // Ambil antrean yang terpanggil hari ini
        $queues = Queue::where('status', 'calling')
            ->whereNotNull('called_at')
            ->whereDate('called_at', $selectedDate)
            ->get();

        $recentCalled = $counters->map(function ($counter) use ($queues) {
            $queue = $queues->where('counter_id', $counter->id)->first();
            return [
                'counter_id'   => $counter->id,
                'counter_name' => $counter->name,
                'service_name' => $counter->service->name ?? '-',
                'queue_id'     => $queue->id ?? null,
                'queue_number' => $queue->number ?? null,
                'highlight'    => false,
            ];
        });

        return Inertia('Display/Monitor', compact('services', 'counters', 'recentCalled'));
    }

    public function waiting()
    {
        return \App\Models\Queue::with('service')
            ->where('status', 'waiting')
            ->whereDate('created_at', today())
            ->orderBy('created_at')
            ->take(10)
            ->get();
    }

    public function calling()
    {
        return \App\Models\Queue::with(['service', 'counter'])
            ->where('status', 'calling')
            ->whereDate('created_at', today())
            ->orderBy('updated_at')
            ->take(10)
            ->get();
    }
}

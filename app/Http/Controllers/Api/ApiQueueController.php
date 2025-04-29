<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;
use App\Services\QueueService;

class ApiQueueController extends Controller
{
    public function callNext($counterId, QueueService $queueService)
    {
        $counter = Counter::findOrFail($counterId);
        $queue   = $queueService->callNextQueue($counter);

        return $queue
        ? response()->json(['message' => 'Memanggil nomor ' . $queue->number])
        : response()->json(['message' => 'Tidak ada antrian'], 404);
    }

    public function finish($queueId, QueueService $queueService)
    {
        $queue = Queue::findOrFail($queueId);
        $queueService->finishQueue($queue);

        return response()->json(['message' => 'Antrean selesai']);
    }

    public function skip($queueId, QueueService $queueService)
    {
        $queue = Queue::findOrFail($queueId);
        $queueService->skipQueue($queue);

        return response()->json(['message' => 'Antrean dilewati']);
    }

    // Dipanggil oleh speaker/display
    public function nextCall()
    {
        $call = Queue::where('status', 'calling')
            ->whereNull('called_at')
            ->whereDate('created_at', today())
            ->orderBy('updated_at')
            ->first();

        if ($call) {
            $call->load(['service', 'counter']);
            $call->update(['called_at' => now()]);
        }

        return response()->json($call);
    }

    // Untuk display: ambil riwayat terakhir
    public function recentCalls()
    {
        // return Queue::whereNotNull('called_at')
        //     ->whereStatus('calling')
        //     ->latest('called_at')
        //     ->with(['service', 'counter'])
        //     ->get()
        //     ->groupBy('counter_id')
        //     ->map(function ($group) {
        //         return $group->first();
        //     })
        //     ->values();

        $selectedDate = today();

        $counters = Counter::with(['service'])->where('is_active', true)->get();

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
            ];
        });

        return response()->json($recentCalled);
    }
}

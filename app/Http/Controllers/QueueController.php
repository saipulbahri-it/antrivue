<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreQueueRequest;
use App\Http\Requests\UpdateQueueRequest;
use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;
use App\Services\QueueService;

class QueueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Queue::with('service')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($queue) {
                return [
                    'number'  => $queue->number,
                    'status'  => $queue->status,
                    'service' => $queue->service->name,
                ];
            });
        return response()->json([
            'queues' => Queue::with('service')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($queue) {
                    return [
                        'number'  => $queue->number,
                        'status'  => $queue->status,
                        'service' => $queue->service->name,
                    ];
                }),
        ]);
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
    public function store(StoreQueueRequest $request, QueueService $queueService)
    {
        $service = Service::findOrFail($request->service_id);
        $queue   = $queueService->createQueue($service);

        return response()->json([
            'message' => 'Antrian berhasil dibuat',
            'number'  => $queue->number,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Queue $queue)
    {
        return response()->json([
            'number' => $queue->number,
            'status' => $queue->status,
        ]);
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
}

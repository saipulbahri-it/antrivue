<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Queue;
use Carbon\Carbon;

class ApiMonitorController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Ambil counter aktif
        $counters = Counter::with(['service'])->where('is_active', true)->get();

        // Ambil antrean yang calling hari ini
        $queues = Queue::where('status', 'calling')
        // ->whereNotNull('called_at')
            ->whereDate('called_at', $today)
            ->get();

        // Gabungkan data counter dan antrean
        $data = $counters->map(function ($counter) use ($queues) {
            $queue = $queues->where('counter_id', $counter->id)->first();
            return [
                'counter_name' => $counter->name,
                'service_name' => $counter->service->name ?? '-',
                'queue_number' => $queue->number ?? null,
            ];
        });

        return response()->json([
            'counters' => $data,
        ]);
    }
}

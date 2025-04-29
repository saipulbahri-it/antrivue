<?php
namespace App\Services;

use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class QueueService
{

    /**
     * Generate a queue number based on the service name and today's date.
     *
     * Format Nomor Antrian
     * Format: [KODE_LAYANAN][NOMOR]
     * Contoh: A001, B012, C103
     * KODE_LAYANAN bisa diambil dari 1 huruf pertama nama layanan (atau dibuat lebih spesifik)
     * Nomor dimulai dari 001 dan naik berdasarkan antrean terakhir hari itu untuk layanan yang sama.

     * @param Service $service
     * @return string
     */
    public function generateQueueNumber(Service $service): string
    {
        $today = Carbon::today();
        // $prefix = strtoupper(Str::substr($service->prefix, 0, 1));
        $prefix = $service->prefix;

        // Cari antrean terakhir hari ini untuk layanan ini
        $lastQueue = Queue::where('service_id', $service->id)
            ->whereDate('created_at', $today)
            ->orderByDesc('id')
            ->first();

        // Ambil nomor terakhir
        $lastNumber = 0;
        if ($lastQueue && preg_match('/\d+$/', $lastQueue->number, $matches)) {
            $lastNumber = (int) $matches[0];
        }

        $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return $prefix . ($prefix ? '-' : '') . $nextNumber;
    }

    public function createQueue(Service $service): Queue
    {
        $number = $this->generateQueueNumber($service);

        return Queue::create([
            'number'     => $number,
            'service_id' => $service->id,
            'status'     => 'waiting',
        ]);
    }

    public function getNextQueueForCounter(Counter $counter, $date = null): ?Queue
    {
        $date = $date ?? Carbon::today();

        return Queue::where('service_id', $counter->service_id)
            ->where('status', 'waiting')
            ->whereDate('created_at', $date)
            ->orderBy('created_at')
            ->first();
    }

    public function callNextQueue(Counter $counter, $date = null): ?Queue
    {
        $date = $date ?? Carbon::today();

        // Finish previous 'calling' queue for this counter
        Queue::where('counter_id', $counter->id)
            ->where('status', 'calling')
            ->whereDate('called_at', Carbon::today())
            ->update(['status' => 'skipped']);

        $nextQueue = $this->getNextQueueForCounter($counter, $date);

        if (! $nextQueue) {
            return null;
        }

        $nextQueue->update([
            'counter_id' => $counter->id,
            'status'     => 'calling',
            // 'called_at'  => now(),
        ]);

        return $nextQueue;
    }

    public function finishQueue(Queue $queue): Queue
    {
        $queue->update([
            'status' => 'done',
        ]);

        return $queue;
    }

    public function skipQueue(Queue $queue): Queue
    {
        $queue->update([
            'status' => 'skipped',
        ]);

        return $queue;
    }
}

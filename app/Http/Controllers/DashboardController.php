<?php
namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $selectedDateInput = request('selectedDate', now());

        if (! \Carbon\Carbon::hasFormat($selectedDateInput, 'Y-m-d')) {
            return redirect()->route('dashboard', [
                'selectedDate' => now()->format('Y-m-d'),
            ]);
        }

        $selectedDate = \Carbon\Carbon::parse($selectedDateInput);

        $users = User::with([
            'counter:id,name',
        ])->get();

        // get queues counts today

        $queuesCountToday = Queue::whereDate('created_at', today())->count();

        // queuesCountToday aggregate by service_id
        $queuesCountTodayByService = Queue::whereDate('created_at', today())
            ->selectRaw('service_id, count(*) as count')
            ->groupBy('service_id')
            ->get();

        // queues Count Today aggregate by status (waiting, calling, done,skiped)
        $queuesCountTodayByStatus = Queue::whereDate('created_at', today())
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        // ServiceCount to dashboard
        $services = Service::withCount([
            'queues as queues_waiting_count' => function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate)->where('status', 'waiting');
            },
            'queues as queues_done_count'    => function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate)->where('status', 'done');
            },
            'queues as queues_calling_count' => function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate)->where('status', 'calling')->whereNull('created_at');
            },
            'queues as queues_called_count' => function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate)->where('status', 'calling')->whereNotNull('created_at');
            },
            'queues as queues_skipped_count' => function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate)->where('status', 'skipped');
            },
            'queues'                         => function ($query) use ($selectedDate) {
                $query->whereDate('created_at', $selectedDate);
            },
        ])->get();

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

        return inertia('Admin/Dashboard', [
            'title'        => 'Dashboard',
            'description'  => 'Selamat datang di aplikasi antrian',
            'services'     => $services,
            'counters'     => $counters,
            'users'        => $users,
            'services'     => $services,
            'selectedDate' => $selectedDate->format('Y-m-d'),
        ]);
    }
}

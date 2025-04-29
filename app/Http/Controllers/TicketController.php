<?php
namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\QueueService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        // Logic to display services
        $services = Service::with(['counters' => function ($query) {
            $query->orderBy('name');
        }])->get();

        return Inertia::render('Ticket', compact('services'));
    }

    public function store(Request $request, QueueService $queueService)
    {
        // Logic to store a new ticket
        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        // Create a new ticket logic here

        $service = Service::findOrFail($request->service_id);

        // $queue = $queueService->createQueue($service);

        // $queue->load('service');

        // if ($request->wantsJson()) {
        //     dd('wantsJson');
        // }

        // if ($request->header('X-Inertia')) {
        //     dd('X-Inertia');
        // }

        $queue = $queueService->createQueue($service);

        $services = Service::with(['counters' => function ($query) {
            $query->orderBy('name');
        }])->get();

        $queue->load(['service', 'counter']);

        return Inertia::render('Ticket', compact('services', 'queue'));

        return redirect()->route('ticket')->with([
            'success' => 'Ticket created successfully.',
        ]);

        return back()->with([
            'success' => 'Ticket created successfully.',
            // 'queue'   => $queue,
        ]);
    }
}

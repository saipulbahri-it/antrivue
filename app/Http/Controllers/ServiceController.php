<?php
namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'name'   => 'required|string|max:255',
            'prefix' => 'nullable|string|max:3',
        ]);

        Service::create($request->only('name', 'prefix'));

        return back()->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'prefix' => 'nullable|string|max:3',
        ]);

        $service->update($request->only('name', 'prefix'));

        return back()->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Check if the service has any queues
        if ($service->queues()->exists()) {
            return back()->withErrors(['Service cannot be deleted because it has associated queues.']);
        }

        // Check if the service has any counters
        if ($service->counters()->exists()) {
            return back()->withErrors(['Service cannot be deleted because it has associated counters.']);
        }

        $service->delete();

        return back()->with('success', 'Service deleted successfully.');
    }
}

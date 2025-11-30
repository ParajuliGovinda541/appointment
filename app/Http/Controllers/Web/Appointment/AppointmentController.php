<?php

namespace App\Http\Controllers\Web\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Officer;
use App\Models\Visitor;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    protected $service;

    public function __construct(AppointmentService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = $this->service->getAll();
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $officers = Officer::all();
        $visitors = Visitor::all();
        return view('appointments.create', compact('officers', 'visitors'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'officer_id' => 'required|exists:officers,id',
            'visitor_id' => 'required|exists:visitors,id',
            'name' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $this->service->store($request->all());

        return redirect()->route('appointments.index')->with('success', 'Appointment Added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $officers = Officer::all();
        $visitors = Visitor::all();
        return view('appointments.edit', compact('appointment', 'officers', 'visitors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->service->update($appointment, $request->all());
        return redirect()->route('appointments.index')->with('success', 'Appointment Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }


    public function cancel(Appointment $appointment)
    {
        $this->service->cancel($appointment);
        return back()->with('success', 'Appointment Cancelled');
    }
}

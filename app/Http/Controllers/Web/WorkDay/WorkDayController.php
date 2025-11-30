<?php

namespace App\Http\Controllers\Web\WorkDay;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\WorkDay;
use App\Services\WorkDayService;
use Illuminate\Http\Request;

class WorkDayController extends Controller
{
    protected $service;

    public function __construct(WorkDayService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $workdays = $this->service->getAll();
        return view('workdays.index', compact('workdays'));
    }

    public function create()
    {
        $officers = Officer::all();
        return view('workdays.create', compact('officers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'officer_id' => 'required|exists:officers,id',
            'day_of_week' => 'required',
        ]);

        $this->service->store($request->all());

        return redirect()->route('workdays.index')->with('success', 'Work Day Added');
    }

    public function edit(WorkDay $workday)
    {
        $officers = Officer::all();
        return view('workdays.edit', compact('workday', 'officers'));
    }

    public function update(Request $request, WorkDay $workday)
    {
        $this->service->update($workday, $request->all());
        return redirect()->route('workdays.index')->with('success', 'Work Day Updated');
    }
}

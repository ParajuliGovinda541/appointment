<?php

namespace App\Http\Controllers\Web\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Officer;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    protected $service;

    public function __construct(ActivityService $service)
    {
        $this->service = $service;
    }

    // List all activities
    public function index()
    {
        $activities = $this->service->getAll();
        return view('activitys.index', compact('activities'));
    }

    // Show create form
    public function create()
    {
        $officers = Officer::where('status', 'Active')->get();
        return view('activitys.create', compact('officers'));
    }

    // Store new activity
    public function store(Request $request)
    {
        $request->validate([
            'officer_id' => 'required|exists:officers,id',
            'type'       => 'required|in:leave,break',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date'   => 'required|after_or_equal:start_date',
            'end_time'   => 'required',
        ]);

        $data = $request->only(['officer_id', 'type', 'start_date', 'start_time', 'end_date', 'end_time']);

        $activity = $this->service->store($data);

        Log::info('Activity created', [
            'activity_id' => $activity->id,
            'officer_id'  => $activity->officer_id,
            'type'        => $activity->type,
            'start_date'  => $activity->start_date,
            'start_time'  => $activity->start_time,
            'end_date'    => $activity->end_date,
            'end_time'    => $activity->end_time,
            'status'      => $activity->status,
        ]);

        return redirect()->route('activitys.index')->with('success', 'Activity created successfully.');
    }

    // Show edit form
    public function edit(Activity $activity)
    {
        $officers = Officer::where('status', 'Active')->get();
        return view('activitys.edit', compact('activity', 'officers'));
    }

    // Update activity
    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'officer_id' => 'required|exists:officers,id',
            'type'       => 'required|in:leave,break',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date'   => 'required|after_or_equal:start_date',
            'end_time'   => 'required',
        ]);

        $this->service->update(
            $activity,
            $request->only(['officer_id', 'type', 'start_date', 'start_time', 'end_date', 'end_time'])
        );

        return redirect()->route('activitys.index')->with('success', 'Activity updated successfully.');
    }

    // Activate activity
    public function activate(Activity $activity)
    {
        $this->service->activate($activity);
        return back()->with('success', 'Activity activated.');
    }

    // Deactivate activity
    public function deactivate(Activity $activity)
    {
        $this->service->deactivate($activity);
        return back()->with('success', 'Activity cancelled.');
    }
}

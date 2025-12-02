<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Officer;
use Illuminate\Validation\ValidationException;

class ActivityService
{
    // List all
    public function getAll()
    {
        return Activity::with('officer')->latest()->get();
    }

    // Store new activity
    public function store(array $data)
    {
        $officer = Officer::findOrFail($data['officer_id']);
        if ($officer->status !== 'Active') {
            throw ValidationException::withMessages(['officer_id' => 'Officer must be active.']);
        }

        $this->validateNoOverlap(
            $officer->id,
            $data['start_date'],
            $data['start_time'],
            $data['end_time']
        );

        return Activity::create($data);
    }

    // Update activity
    public function update(Activity $activity, array $data)
    {
        $officer = Officer::findOrFail($data['officer_id']);
        if ($officer->status !== 'Active') {
            throw ValidationException::withMessages(['officer_id' => 'Officer must be active.']);
        }

        $this->validateNoOverlapForUpdate(
            $officer->id,
            $data['start_date'],
            $data['start_time'],
            $data['end_time'],
            $activity->id
        );

        $activity->update($data);

        return $activity;
    }

    // Validate overlap for create
    private function validateNoOverlap($officerId, $date, $start, $end)
    {
        $overlap = Activity::where('officer_id', $officerId)
            ->where('status', 'Active')
            ->where('start_date', $date)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(fn($q) => $q->where('start_time', '<=', $start)->where('end_time', '>=', $end));
            })->exists();

        if ($overlap) {
            throw ValidationException::withMessages(['time' => 'Officer busy during this time.']);
        }
    }

    // Validate overlap for update (excluding the current record)
    private function validateNoOverlapForUpdate($officerId, $date, $start, $end, $activityId)
    {
        $overlap = Activity::where('officer_id', $officerId)
            ->where('id', '!=', $activityId)
            ->where('status', 'Active')
            ->where('start_date', $date)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(fn($q) => $q->where('start_time', '<=', $start)->where('end_time', '>=', $end));
            })
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages(['time' => 'Officer busy during this time.']);
        }
    }

    // Activate activity
    public function activate(Activity $activity)
    {
        $activity->update(['status' => 'Active']);
    }

    // Deactivate / cancel activity
    public function deactivate(Activity $activity)
    {
        $activity->update(['status' => 'Cancelled']);
    }
}

<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Officer;
use Illuminate\Support\Facades\Log;
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

    public function getFiltered(array $filters = [])
    {
        $query = Activity::with(['officer', 'officer.appointments']);

        \Log::info('Activity Filter Request', $filters); // log the incoming filters

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
            \Log::info('Filtering by type', ['type' => $filters['type']]);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
            \Log::info('Filtering by status', ['status' => $filters['status']]);
        }

        if (!empty($filters['officer_id'])) {
            $query->where('officer_id', $filters['officer_id']);
            \Log::info('Filtering by officer_id', ['officer_id' => $filters['officer_id']]);
        }

        if (!empty($filters['visitor_id'])) {
            $query->whereHas('officer.appointments', function ($q) use ($filters) {
                $q->where('visitor_id', $filters['visitor_id']);
                \Log::info('Filtering by visitor_id in appointments', ['visitor_id' => $filters['visitor_id']]);
            });
        }

        if (!empty($filters['start_date'])) {
            $query->where('start_date', '>=', $filters['start_date']);
            \Log::info('Filtering by start_date', ['start_date' => $filters['start_date']]);
        }

        if (!empty($filters['end_date'])) {
            $query->where('end_date', '<=', $filters['end_date']);
            Log::info('Filtering by end_date', ['end_date' => $filters['end_date']]);
        }

        if (!empty($filters['start_time'])) {
            $query->where('start_time', '>=', $filters['start_time']);
            Log::info('Filtering by start_time', ['start_time' => $filters['start_time']]);
        }

        if (!empty($filters['end_time'])) {
            $query->where('end_time', '<=', $filters['end_time']);
            Log::info('Filtering by end_time', ['end_time' => $filters['end_time']]);
        }

        $sql = $query->toSql();
        $bindings = $query->getBindings();
        Log::info('Final Activity Query', ['sql' => $sql, 'bindings' => $bindings]);

        return $query->latest()->get();
    }
}

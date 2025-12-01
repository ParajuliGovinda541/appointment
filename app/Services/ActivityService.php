<?php


namespace App\Services;

use App\Models\Activity;
use Carbon\Carbon;

class ActivityService
{
    public function create(array $data)
    {

        return Activity::create($data);
    }

    public function update(Activity $activity, array $data)
    {
        $activity->update($data);
        return $activity;
    }

    public function activate(Activity $activity)
    {
        $activity->update(['status' => 'Active']);
        return $activity;
    }

    public function deactivate(Activity $activity)
    {
        $activity->update(['status' => 'Cancelled']);
        return $activity;
    }

    public function list($filters = [])
    {
        $query = Activity::with('officer');

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['officer'])) {
            $query->where('officer_id', $filters['officer']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('start_date', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->latest()->get();
    }
}

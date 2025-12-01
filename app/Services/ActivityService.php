<?php


namespace App\Services;

use App\Models\Activity;
use Carbon\Carbon;

class ActivityService
{
    public function getAll()
    {
        return Activity::all();
    }
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
}

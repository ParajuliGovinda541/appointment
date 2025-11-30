<?php

namespace App\Services;

use App\Models\WorkDay;

/**
 * Class WorkDayService.
 */
class WorkDayService
{
    public function getAll()
    {
        return WorkDay::with('officer')->latest()->get();
    }

    public function store($data)
    {
        return WorkDay::create([
            'officer_id' => $data['officer_id'],
            'day_of_week' => $data['day_of_week'],
        ]);
    }

    public function update(WorkDay $workDay, $data)
    {
        return $workDay->update([
            'officer_id' => $data['officer_id'],
            'day_of_week' => $data['day_of_week'],
        ]);
    }

    public function delete(WorkDay $workDay)
    {
        return $workDay->delete();
    }
}

<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Officer;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ActivityService
{

    public function store(array $data)
    {
        $officer = Officer::findOrFail($data['officer_id']);
        if ($officer->status !== 'Active') {
            throw ValidationException::withMessages(['officer_id' => 'Officer must be active.']);
        }

        $this->validateNoOverlap($officer->id, $data['start_date'], $data['start_time'], $data['end_time']);

        return Activity::create($data);
    }

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

        if ($overlap) throw ValidationException::withMessages(['time' => 'Officer busy during this time.']);
    }
}

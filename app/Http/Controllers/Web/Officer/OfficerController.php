<?php

namespace App\Services;

use App\Models\Officer;
use App\Models\Activity;
use App\Models\WorkDay;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OfficerService
{
    /**
     * Get all officers with their posts.
     */
    public function getAll()
    {
        return Officer::with('post')->latest()->get();
    }

    /**
     * Create a new officer with workdays.
     */
    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $officer = Officer::create([
                'name'            => $data['name'],
                'post_id'         => $data['post_id'],
                'work_start_time' => $data['work_start_time'],
                'work_end_time'   => $data['work_end_time'],
                'status'          => $data['status'] ?? 'Active',
            ]);

            // Add work days if provided
            if (!empty($data['work_days'])) {
                foreach ($data['work_days'] as $day) {
                    WorkDay::create([
                        'officer_id' => $officer->id,
                        'day_of_week' => $day
                    ]);
                }
            }

            return $officer;
        });
    }

    /**
     * Get officer by ID with post.
     */
    public function getById($id)
    {
        return Officer::with('post')->findOrFail($id);
    }

    /**
     * Get officer with appointments.
     */
    public function appointments($id)
    {
        return Officer::with('appointments')->findOrFail($id);
    }

    /**
     * Update officer details including work days and adjust future activities.
     */
    public function update($officer, $data)
    {
        return DB::transaction(function () use ($officer, $data) {

            // Update basic officer info
            $officer->update([
                'name'            => $data['name'],
                'post_id'         => $data['post_id'],
                'work_start_time' => $data['work_start_time'],
                'work_end_time'   => $data['work_end_time'],
            ]);

            // Update work days if provided
            if (isset($data['work_days'])) {
                // Delete old workdays
                $officer->workDays()->delete();

                // Add new workdays
                foreach ($data['work_days'] as $day) {
                    WorkDay::create([
                        'officer_id' => $officer->id,
                        'day_of_week' => $day
                    ]);
                }

                // Cancel future activities that fall outside new work schedule
                $now = Carbon::now();

                Activity::where('officer_id', $officer->id)
                    ->where('status', 'Active')
                    ->where(function ($q) use ($data) {
                        $q->where('start_time', '<', $data['work_start_time'])
                            ->orWhere('end_time', '>', $data['work_end_time']);
                    })
                    ->whereDate('start_date', '>', $now)
                    ->update(['status' => 'Inactive']);

                // Cancel activities on days not in work_days
                $excludedDays = array_diff(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], $data['work_days']);
                Activity::where('officer_id', $officer->id)
                    ->where('status', 'Active')
                    ->whereIn(DB::raw('DAYNAME(start_date)'), $excludedDays)
                    ->whereDate('start_date', '>', $now)
                    ->update(['status' => 'Inactive']);
            }

            return $officer;
        });
    }

    /**
     * Activate officer and future activities where visitor is active.
     * Prevent activation if officer's post is inactive.
     */
    public function activate($officer)
    {
        if ($officer->post->status !== 'Active') {
            return [
                'success' => false,
                'message' => 'Cannot activate officer: Post is inactive.'
            ];
        }

        $officer->update(['status' => 'Active']);

        $now = Carbon::now();

        // Reactivate future activities where visitor is active
        $officer->activities()
            ->where('status', 'Inactive')
            ->whereDate('start_date', '>', $now)
            ->get()
            ->each(function ($activity) {
                if (isset($activity->visitor) && $activity->visitor->status === 'Active') {
                    $activity->update(['status' => 'Active']);
                }
            });

        return [
            'success' => true,
            'message' => 'Officer activated. Future activities with active visitors reactivated.'
        ];
    }

    /**
     * Deactivate officer and all future active activities.
     */
    public function deactivate($officer)
    {
        $officer->update(['status' => 'Inactive']);

        $now = Carbon::now();

        // Deactivate all future active activities (appointments, leave, break)
        $officer->activities()
            ->where('status', 'Active')
            ->whereDate('start_date', '>', $now)
            ->update(['status' => 'Inactive']);

        return [
            'success' => true,
            'message' => 'Officer and future activities deactivated successfully.'
        ];
    }
}

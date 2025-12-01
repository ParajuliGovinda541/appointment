<?php

namespace App\Services;

use App\Models\Officer;

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
     * Create a new officer.
     */
    public function store($data)
    {
        return Officer::create([
            'name'            => $data['name'],
            'post_id'         => $data['post_id'],
            'work_start_time' => $data['work_start_time'],
            'work_end_time'   => $data['work_end_time'],
            'status'          => $data['status'] ?? 'Active',
        ]);
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
     * Update officer details.
     */
    public function update($officer, $data)
    {
        return $officer->update([
            'name'            => $data['name'],
            'post_id'         => $data['post_id'],
            'work_start_time' => $data['work_start_time'],
            'work_end_time'   => $data['work_end_time'],
        ]);
    }

    /**
     * Activate officer and future appointments where visitor is active.
     */
    public function activate($officer)
    {
        // Activate the officer
        $officer->update(['status' => 'Active']);

        // Reactivate only future appointments where visitor is active
        $officer->appointments()
            ->where('status', 'Inactive')
            ->get()
            ->each(function ($appointment) {
                $appointmentEnd = strtotime($appointment->date . ' ' . $appointment->end_time);
                $now = time();

                if ($appointmentEnd >= $now && $appointment->visitor->status === 'Active') {
                    $appointment->update(['status' => 'Active']);
                }
            });

        return [
            'success' => true,
            'message' => 'Officer activated. Future appointments with active visitors reactivated only.'
        ];
    }

    /**
     * Deactivate officer and all future active appointments.
     */
    public function deactivate($officer)
    {
        // Deactivate the officer
        $officer->update(['status' => 'Inactive']);

        // Deactivate only future active appointments
        $officer->appointments()
            ->where('status', 'Active')
            ->get()
            ->each(function ($appointment) {
                $appointmentDateTime = strtotime($appointment->date . ' ' . $appointment->end_time);
                $now = time();

                if ($appointmentDateTime >= $now) {
                    $appointment->update(['status' => 'Inactive']);
                }
            });

        return [
            'success' => true,
            'message' => 'Officer and future appointments deactivated successfully.'
        ];
    }
}

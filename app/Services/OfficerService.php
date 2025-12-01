<?php

namespace App\Services;

use App\Models\Officer;
use Illuminate\Support\Facades\DB;

class OfficerService
{
    /**
     * Class OfficerService.
     */
    public function getAll()
    {
        return Officer::with('post')->latest()->get();
    }


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

    public function getById($id)
    {
        return Officer::with('post')->findOrFail($id);
    }

    public function appointments($id)
    {
        return Officer::with('appointments')->findOrFail($id);
    }


    public function update($officer, $data)
    {
        return $officer->update([
            'name'            => $data['name'],
            'post_id'         => $data['post_id'],
            'work_start_time' => $data['work_start_time'],
            'work_end_time'   => $data['work_end_time'],
        ]);
    }
    // public function activate($officer)
    // {
    //     // Activate the officer
    //     $officer->update(['status' => 'Active']);

    //     // Reactivate future appointments only if the visitor is active
    //     $officer->appointments()
    //         ->where('status', 'Inactive')
    //         ->get()
    //         ->each(function ($appointment) {
    //             $appointmentDateTime = strtotime($appointment->date . ' ' . $appointment->end_time);
    //             $now = time();

    //             // Only consider future appointments (end time in the future)
    //             if ($appointmentDateTime >= $now && $appointment->visitor->status === 'Active') {
    //                 $appointment->update(['status' => 'Active']);
    //             }
    //         });

    //     return [
    //         'success' => true,
    //         'message' => 'Officer and future appointments reactivated successfully (past appointments and inactive visitors skipped).'
    //     ];
    // }

    public function activate($officer)
    {
        // Activate the officer
        $officer->update(['status' => 'Active']);

        $officer->appointments()
            ->where('status', 'Inactive')
            ->get()
            ->each(function ($appointment) {
                $appointmentEnd = strtotime($appointment->date . ' ' . $appointment->end_time);
                $now = time();

                if ($appointmentEnd >= $now) {
                    if ($appointment->visitor->status === 'Active') {
                        $appointment->update(['status' => 'Active']);
                    }
                }
            });

        return [
            'success' => true,
            'message' => 'Officer activated. Future appointments with active visitors reactivated only.'
        ];
    }

    public function deactivate($officer)
    {
        // Deactivate the officer
        $officer->update(['status' => 'Inactive']);

        // Deactivate all future active appointments
        $officer->appointments()
            ->where('status', 'Active')
            ->get()
            ->each(function ($appointment) {
                $appointmentDateTime = strtotime($appointment->date . ' ' . $appointment->end_time);
                $now = time();

                // Only future appointments
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

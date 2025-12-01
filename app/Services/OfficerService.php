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


    public function activate($officer)
    {
        return $officer->update(['status' => 'Active']);
    }


    public function deactivate($officer)
    {
        // Deactivate officer
        $officer->update(['status' => 'Inactive']);

        // Deactivate all future active appointments related to this officer
        $officer->appointments()
            ->where('status', 'Active')
            ->get()
            ->filter(function ($appointment) {
                return strtotime($appointment->date) >= strtotime(now()->toDateString());
            })
            ->each(function ($appointment) {
                $appointment->update(['status' => 'Inactive']);
            });


        // Optional: If you also track activities separately
        // $officer->activities()->where('status', 'Active')->where('date', '>=', now()->toDateString())->update(['status' => 'Inactive']);

        return [
            'success' => true,
            'message' => 'Officer and future appointments deactivated successfully.'
        ];
    }
}

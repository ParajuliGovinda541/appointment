<?php

namespace App\Services;

use App\Models\Appointment;

/**
 * Class AppointmentService.
 */
class AppointmentService
{
    public function getAll()
    {
        return Appointment::with(['officer', 'visitor'])->latest()->get();
    }

    public function store($data)
    {
        return Appointment::create($data);
    }

    public function update(Appointment $appointment, $data)
    {
        return $appointment->update($data);
    }

    public function cancel(Appointment $appointment)
    {
        return $appointment->update(['status' => 'Cancelled']);
    }
}

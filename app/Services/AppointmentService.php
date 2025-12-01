<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

use App\Models\Appointment;
use App\Models\Officer;
use Illuminate\Support\Facades\Log;

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
        $officer = Officer::find($data['officer_id']);

        // Log incoming request
        Log::info('Attempting to create appointment:', $data);

        if ($officer->status !== 'Active') {
            Log::warning('Cannot add appointment: Officer inactive', ['officer_id' => $data['officer_id']]);
            throw ValidationException::withMessages([
                'officer_id' => 'Cannot add appointment: Officer is inactive.'
            ]);
        }

        $dayOfWeek = date('l', strtotime($data['date']));
        $isWorkDay = $officer->workDays()->where('day_of_week', $dayOfWeek)->exists();

        if (!$isWorkDay) {
            Log::warning('Appointment date is not in officer work days', ['date' => $data['date'], 'officer_id' => $data['officer_id']]);
            throw ValidationException::withMessages([
                'date' => 'Appointment date is not in officer’s work days.'
            ]);
        }

        $workStart = $officer->work_start_time;
        $workEnd   = $officer->work_end_time;

        if ($data['start_time'] < $workStart || $data['end_time'] > $workEnd) {
            Log::warning('Appointment outside officer working hours', [
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'work_start' => $workStart,
                'work_end' => $workEnd
            ]);
            throw ValidationException::withMessages([
                'start_time' => "Appointment must be within officer working hours ($workStart - $workEnd)."
            ]);
        }

        // Prevent overlapping appointment
        $overlap = Appointment::where('officer_id', $data['officer_id'])
            ->where('date', $data['date'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->orWhere(function ($q2) use ($data) {
                        $q2->where('start_time', '<=', $data['start_time'])
                            ->where('end_time', '>=', $data['end_time']);
                    });
            })
            ->exists();

        if ($overlap) {
            Log::warning('Appointment overlaps with existing one', ['officer_id' => $data['officer_id'], 'date' => $data['date']]);
            throw ValidationException::withMessages([
                'start_time' => 'This officer already has an appointment during this time.'
            ]);
        }

        // Log successful creation
        Log::info('Appointment created successfully', $data);

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

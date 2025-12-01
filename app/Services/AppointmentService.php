<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use App\Models\Appointment;
use App\Models\Officer;
use App\Models\Activity;
use Illuminate\Support\Facades\Log;

/**
 * Class AppointmentService.
 */
class AppointmentService
{
    /**
     * Get all appointments with officer and visitor details.
     */
    public function getAll()
    {
        return Appointment::with(['officer', 'visitor'])->latest()->get();
    }

    /**
     * Store a new appointment and create corresponding activity.
     */
    public function store($data)
    {
        $officer = Officer::find($data['officer_id']);

        // Log incoming request
        Log::info('Attempting to create appointment:', $data);

        // Officer must be active
        if ($officer->status !== 'Active') {
            Log::warning('Cannot add appointment: Officer inactive', ['officer_id' => $data['officer_id']]);
            throw ValidationException::withMessages([
                'officer_id' => 'Cannot add appointment: Officer is inactive.'
            ]);
        }

        // Check officer's workday
        $dayOfWeek = date('l', strtotime($data['date']));
        $isWorkDay = $officer->workDays()->where('day_of_week', $dayOfWeek)->exists();
        if (!$isWorkDay) {
            Log::warning('Appointment date is not in officer work days', ['date' => $data['date'], 'officer_id' => $data['officer_id']]);
            throw ValidationException::withMessages([
                'date' => 'Appointment date is not in officer’s work days.'
            ]);
        }

        // Check work time
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

        // Prevent overlapping appointments
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

        // Create appointment
        $appointment = Appointment::create($data);

        // Create corresponding activity
        Activity::create([
            'officer_id' => $officer->id,
            'type'       => 'Appointment',
            'start_date' => $appointment->date,
            'start_time' => $appointment->start_time,
            'end_date'   => $appointment->date,
            'end_time'   => $appointment->end_time,
            'status'     => 'Active',
        ]);

        Log::info('Appointment and activity created successfully', $data);

        return $appointment;
    }

    /**
     * Update appointment and sync its activity.
     */
    public function update(Appointment $appointment, $data)
    {
        $appointment->update($data);

        // Sync corresponding activity
        $activity = Activity::where('officer_id', $appointment->officer_id)
            ->where('type', 'Appointment')
            ->where('start_date', $appointment->date)
            ->where('start_time', $appointment->start_time)
            ->first();

        if ($activity) {
            $activity->update([
                'start_date' => $appointment->date,
                'start_time' => $appointment->start_time,
                'end_date'   => $appointment->date,
                'end_time'   => $appointment->end_time,
                'status'     => $appointment->status === 'Cancelled' ? 'Inactive' : $appointment->status,
            ]);
        }

        Log::info('Appointment and activity updated successfully', ['appointment_id' => $appointment->id]);

        return $appointment;
    }

    /**
     * Cancel appointment and deactivate corresponding activity.
     */
    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'Cancelled']);

        $activity = Activity::where('officer_id', $appointment->officer_id)
            ->where('type', 'Appointment')
            ->where('start_date', $appointment->date)
            ->where('start_time', $appointment->start_time)
            ->first();

        if ($activity) {
            $activity->update(['status' => 'Inactive']);
        }

        Log::info('Appointment cancelled and activity marked inactive', ['appointment_id' => $appointment->id]);

        return $appointment;
    }
}

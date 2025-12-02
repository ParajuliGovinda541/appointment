<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use App\Models\Appointment;
use App\Models\Officer;
use App\Models\Visitor;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AppointmentService
{
    /**
     * Get all appointments with officer and visitor details.
     */
    public function getAll()
    {
        return Appointment::with(['officer', 'visitor'])->latest()->get();
    }


    public function store($data)
    {
        Log::info("Creating appointment", $data);

        $officer = Officer::findOrFail($data['officer_id']);
        $visitor = Visitor::findOrFail($data['visitor_id']);

        $date = $data['date'];
        $start = $data['start_time'];
        $end = $data['end_time'];

        $startDT = Carbon::parse($date . ' ' . $start);
        $endDT   = Carbon::parse($date . ' ' . $end);


        if ($startDT->isPast()) {
            throw ValidationException::withMessages([
                'date' => 'You cannot create an appointment in the past.',
            ]);
        }


        if ($officer->status !== 'Active') {
            throw ValidationException::withMessages([
                'officer_id' => 'Cannot add appointment: Officer is inactive.'
            ]);
        }


        if ($visitor->status !== 'Active') {
            throw ValidationException::withMessages([
                'visitor_id' => 'Cannot add appointment: Visitor is inactive.'
            ]);
        }


        $dayName = Carbon::parse($date)->format('l');

        $isWorkDay = $officer->workDays()
            ->where('day_of_week', $dayName)
            ->exists();

        if (!$isWorkDay) {
            throw ValidationException::withMessages([
                'date' => "Officer is not available on $dayName.",
            ]);
        }

        if ($start < $officer->work_start_time || $end > $officer->work_end_time) {
            throw ValidationException::withMessages([
                'start_time' => "Appointment must be between {$officer->work_start_time} and {$officer->work_end_time}."
            ]);
        }


        $officerBusy = Activity::where('officer_id', $officer->id)
            ->where('status', 'Active')
            ->where(function ($q) use ($date, $start, $end) {
                $q->where('start_date', $date)->where(function ($q2) use ($start, $end) {
                    $q2->whereBetween('start_time', [$start, $end])
                        ->orWhereBetween('end_time', [$start, $end])
                        ->orWhere(function ($q3) use ($start, $end) {
                            $q3->where('start_time', '<=', $start)
                                ->where('end_time', '>=', $end);
                        });
                });
            })
            ->exists();

        if ($officerBusy) {
            throw ValidationException::withMessages([
                'date' => 'Officer is busy, on leave, or on break during this time.'
            ]);
        }


        $visitorBusy = Appointment::where('visitor_id', $visitor->id)
            ->where('status', 'Active')
            ->where('date', $date)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_time', '<=', $start)
                            ->where('end_time', '>=', $end);
                    });
            })
            ->exists();

        if ($visitorBusy) {
            throw ValidationException::withMessages([
                'visitor_id' => 'Visitor already has an active appointment during this time.'
            ]);
        }


        $overridden = Activity::where('officer_id', $officer->id)
            ->where('status', 'Inactive')
            ->where('type', '!=', 'Appointment')
            ->where('start_date', $date)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_time', '<=', $start)
                            ->where('end_time', '>=', $end);
                    });
            })
            ->get();

        foreach ($overridden as $activity) {
            $activity->update(['status' => 'Cancelled']);
        }


        $appointment = Appointment::create($data);


        Activity::create([
            'officer_id' => $officer->id,
            'type'       => 'Appointment',
            'start_date' => $date,
            'start_time' => $start,
            'end_date'   => $date,
            'end_time'   => $end,
            'status'     => 'Active',
        ]);

        return $appointment;
    }

    public function update(Appointment $appointment, $data)
    {
        $appointment->update($data);

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
                'status'     => $appointment->status === 'Cancelled' ? 'Inactive' : 'Active',
            ]);
        }

        return $appointment;
    }


    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'Cancelled']);

        Activity::where('officer_id', $appointment->officer_id)
            ->where('type', 'Appointment')
            ->where('start_date', $appointment->date)
            ->where('start_time', $appointment->start_time)
            ->update(['status' => 'Inactive']);

        return $appointment;
    }
}

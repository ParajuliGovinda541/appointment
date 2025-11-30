@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Appointment</h1>

    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" class="max-w-md">
        @csrf
        @method('PUT')

        <!-- Appointment Name -->
        <div class="mb-4">
            <label for="name" class="block mb-2 font-medium">Appointment Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $appointment->name) }}"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>

        <!-- Officer -->
        <div class="mb-4">
            <label for="officer_id" class="block mb-2 font-medium">Officer</label>
            <select name="officer_id" id="officer_id" class="border border-gray-300 p-2 rounded w-full bg-white" required>
                <option value="">Select Officer</option>
                @foreach ($officers as $officer)
                    <option value="{{ $officer->id }}" {{ $officer->id == $appointment->officer_id ? 'selected' : '' }}>
                        {{ $officer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Visitor -->
        <div class="mb-4">
            <label for="visitor_id" class="block mb-2 font-medium">Visitor</label>
            <select name="visitor_id" id="visitor_id" class="border border-gray-300 p-2 rounded w-full bg-white" required>
                <option value="">Select Visitor</option>
                @foreach ($visitors as $visitor)
                    <option value="{{ $visitor->id }}" {{ $visitor->id == $appointment->visitor_id ? 'selected' : '' }}>
                        {{ $visitor->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Date -->
        <div class="mb-4">
            <label for="date" class="block mb-2 font-medium">Date</label>
            <input type="date" name="date" id="date" value="{{ old('date', $appointment->date) }}"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>

        <!-- Start and End Time -->
        <div class="mb-4 flex gap-2">
            <div class="flex-1">
                <label for="start_time" class="block mb-2 font-medium">Start Time</label>
                <input type="time" name="start_time" id="start_time"
                    value="{{ old('start_time', $appointment->start_time) }}"
                    class="border border-gray-300 p-2 rounded w-full" required>
            </div>
            <div class="flex-1">
                <label for="end_time" class="block mb-2 font-medium">End Time</label>
                <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $appointment->end_time) }}"
                    class="border border-gray-300 p-2 rounded w-full" required>
            </div>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label for="status" class="block mb-2 font-medium">Status</label>
            <select name="status" id="status" class="border border-gray-300 p-2 rounded w-full bg-white" required>
                @foreach (['Active', 'Cancelled', 'Deactivated', 'Completed'] as $status)
                    <option value="{{ $status }}" {{ $appointment->status == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Update Appointment
        </button>
        <a href="{{ route('appointments.index') }}" class="ml-2 text-gray-500 hover:underline">Back</a>
    </form>
@endsection

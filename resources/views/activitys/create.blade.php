@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Add Activity</h1>

    <form action="{{ route('activitys.store') }}" method="POST" class="max-w-md">
        @csrf

        <!-- Officer Dropdown -->
        <div class="mb-4">
            <label for="officer_id" class="block mb-2 font-medium">Officer</label>
            <select name="officer_id" id="officer_id" class="border border-gray-300 p-2 rounded w-full" required>
                <option value="">Select Officer</option>
                @foreach ($officers as $officer)
                    <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Type: Leave or Break -->
        <div class="mb-4">
            <label for="type" class="block mb-2 font-medium">Activity Type</label>
            <select name="type" id="type" class="border border-gray-300 p-2 rounded w-full" required>
                <option value="">Select Type</option>
                <option value="leave">Leave</option>
                <option value="break">Break</option>
            </select>
        </div>

        <!-- Start Date -->
        <div class="mb-4">
            <label for="start_date" class="block mb-2 font-medium">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="border border-gray-300 p-2 rounded w-full"
                required>
        </div>

        <!-- Start Time -->
        <div class="mb-4">
            <label for="start_time" class="block mb-2 font-medium">Start Time</label>
            <input type="time" name="start_time" id="start_time" class="border border-gray-300 p-2 rounded w-full"
                required>
        </div>

        <!-- End Date -->
        <div class="mb-4">
            <label for="end_date" class="block mb-2 font-medium">End Date</label>
            <input type="date" name="end_date" id="end_date" class="border border-gray-300 p-2 rounded w-full" required>
        </div>

        <!-- End Time -->
        <div class="mb-4">
            <label for="end_time" class="block mb-2 font-medium">End Time</label>
            <input type="time" name="end_time" id="end_time" class="border border-gray-300 p-2 rounded w-full" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add Activity
        </button>
        <a href="{{ route('activitys.index') }}" class="ml-2 text-gray-500 hover:underline">Back</a>
    </form>
@endsection

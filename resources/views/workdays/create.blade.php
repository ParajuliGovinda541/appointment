@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Add Work Day</h1>

    <form action="{{ route('workdays.store') }}" method="POST" class="max-w-md">
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

        <!-- Day of Week -->
        <div class="mb-4">
            <label for="day_of_week" class="block mb-2 font-medium">Day of Week</label>
            <select name="day_of_week" id="day_of_week" class="border border-gray-300 p-2 rounded w-full" required>
                <option value="">Select Day</option>
                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                    <option value="{{ $day }}">{{ $day }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add
        </button>
        <a href="{{ route('workdays.index') }}" class="ml-2 text-gray-500 hover:underline">Back</a>
    </form>
@endsection

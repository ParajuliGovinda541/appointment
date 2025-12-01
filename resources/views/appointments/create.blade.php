@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Add Appointment</h1>

    <!-- Global Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('appointments.store') }}" method="POST" class="max-w-md">
        @csrf

        <!-- Appointment Name -->
        <div class="mb-4">
            <label for="name" class="block mb-2 font-medium">Appointment Name</label>
            <input type="text" name="name" id="name" placeholder="Enter appointment name"
                class="border border-gray-300 p-2 rounded w-full @error('name') border-red-500 @enderror"
                value="{{ old('name') }}" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Officer -->
        <div class="mb-4">
            <label for="officer_id" class="block mb-2 font-medium">Officer</label>
            <select name="officer_id" id="officer_id"
                class="border border-gray-300 p-2 rounded w-full bg-white @error('officer_id') border-red-500 @enderror"
                required>
                <option value="">Select Officer</option>
                @foreach ($officers as $officer)
                    <option value="{{ $officer->id }}" {{ old('officer_id') == $officer->id ? 'selected' : '' }}>
                        {{ $officer->name }}
                    </option>
                @endforeach
            </select>
            @error('officer_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Visitor -->
        <div class="mb-4">
            <label for="visitor_id" class="block mb-2 font-medium">Visitor</label>
            <select name="visitor_id" id="visitor_id"
                class="border border-gray-300 p-2 rounded w-full bg-white @error('visitor_id') border-red-500 @enderror"
                required>
                <option value="">Select Visitor</option>
                @foreach ($visitors as $visitor)
                    <option value="{{ $visitor->id }}" {{ old('visitor_id') == $visitor->id ? 'selected' : '' }}>
                        {{ $visitor->name }}
                    </option>
                @endforeach
            </select>
            @error('visitor_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Date -->
        <div class="mb-4">
            <label for="date" class="block mb-2 font-medium">Date</label>
            <input type="date" name="date" id="date"
                class="border border-gray-300 p-2 rounded w-full @error('date') border-red-500 @enderror"
                value="{{ old('date') }}" required>
            @error('date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Start and End Time -->
        <div class="mb-4 flex gap-2">
            <div class="flex-1">
                <label for="start_time" class="block mb-2 font-medium">Start Time</label>
                <input type="time" name="start_time" id="start_time"
                    class="border border-gray-300 p-2 rounded w-full @error('start_time') border-red-500 @enderror"
                    value="{{ old('start_time') }}" required>
                @error('start_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex-1">
                <label for="end_time" class="block mb-2 font-medium">End Time</label>
                <input type="time" name="end_time" id="end_time"
                    class="border border-gray-300 p-2 rounded w-full @error('end_time') border-red-500 @enderror"
                    value="{{ old('end_time') }}" required>
                @error('end_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add Appointment
        </button>
        <a href="{{ route('appointments.index') }}" class="ml-2 text-gray-500 hover:underline">Back</a>
    </form>
@endsection

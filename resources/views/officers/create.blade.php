@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Add Officer</h1>

    <form action="{{ route('officers.store') }}" method="POST" class="max-w-md">
        @csrf
        <div class="mb-4">
            <label for="name" class="block mb-2 font-medium">Officer Name</label>
            <input type="text" name="name" id="name" placeholder="Enter Officer name"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>
        <div class="mb-4">
            <label for="work_start_time" class="block mb-2 font-medium">Work Start Time</label>
            <input type="time" name="work_start_time" id="work_start_time"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>

        <div class="mb-4">
            <label for="work_end_time" class="block mb-2 font-medium">Work End Time</label>
            <input type="time" name="work_end_time" id="work_end_time" class="border border-gray-300 p-2 rounded w-full"
                required>
        </div>

        <label for="post_id" class="block mb-2 font-medium">Post</label>
        <select name="post_id" id="post_id" class="border border-gray-300 p-2 rounded w-full bg-white" required>
            <option value="">Select Post</option>
            @foreach ($posts as $post)
                <option value="{{ $post->id }}">{{ $post->name }}</option>
            @endforeach
        </select>


        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add Officer
        </button>
        <a href="{{ route('officers.index') }}" class="ml-2 text-gray-500 hover:underline">Back</a>
    </form>
@endsection

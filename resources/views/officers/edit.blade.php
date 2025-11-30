@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Officer Detail</h1>

    <form action="{{ route('officers.update', $officer->id) }}" method="POST" class="max-w-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block mb-2 font-medium">Officer Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $officer->name) }}"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>
        <div class="mb-4">
            <label for="work_start_time" class="block mb-2 font-medium">officer work_start_time</label>
            <input type="time" name="work_start_time" id="work_start_time"
                value="{{ old('work_start_time', $officer->work_start_time) }}"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>
        <div class="mb-4">
            <label for="work_end_time" class="block mb-2 font-medium">officer work_end_time</label>
            <input type="time" name="work_end_time" id="work_end_time"
                value="{{ old('work_end_time', $officer->work_end_time) }}"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>

        <div class="mb-4">
            <label for="post_id" class="block mb-2 font-medium">Post</label>

            <select name="post_id" id="post_id" class="border border-gray-300 p-2 rounded w-full bg-white" required>
                <option value="">Select Post</option>

                @foreach ($posts as $post)
                    <option value="{{ $post->id }}" {{ $post->id == $officer->post_id ? 'selected' : '' }}>
                        {{ $post->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Update officer
        </button>
        <a href="{{ route('officers.index') }}" class="ml-2 text-gray-500 hover:underline">Back</a>
    </form>
@endsection

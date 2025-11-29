@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Add Post</h1>

    <form action="{{ route('posts.store') }}" method="POST" class="max-w-md">
        @csrf
        <div class="mb-4">
            <label for="name" class="block mb-2 font-medium">Post Name</label>
            <input type="text" name="name" id="name" placeholder="Enter post name"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add Post
        </button>
        <a href="{{ route('posts.index') }}" class="ml-2 text-gray-500 hover:underline">Back</a>
    </form>
@endsection

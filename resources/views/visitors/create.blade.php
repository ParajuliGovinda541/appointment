@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Add Visitor</h1>

    <form action="{{ route('visitors.store') }}" method="POST" class="max-w-md">
        @csrf
        <div class="mb-4">
            <label for="name" class="block mb-2 font-medium">Visitor Name</label>
            <input type="text" name="name" id="name" placeholder="Enter visitor name"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block mb-2 font-medium">Email Address</label>
            <input type="email" name="email" id="email" placeholder="Enter Visitor Email"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>
        <div class="mb-4">
            <label for="mobile_no" class="block mb-2 font-medium">Mobile Number</label>
            <input type="text" name="mobile_no" id="mobile_no" placeholder="Enter Mobile Number"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add Visitor
        </button>
        <a href="{{ route('visitors.index') }}" class="ml-2 text-gray-500 hover:underline">Back</a>
    </form>
@endsection

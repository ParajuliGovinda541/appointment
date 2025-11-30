@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Visitor Detail</h1>

    <form action="{{ route('visitors.update', $visitor->id) }}" method="POST" class="max-w-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block mb-2 font-medium">Visitor Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $visitor->name) }}"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block mb-2 font-medium">Visitor Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $visitor->email) }}"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>
        <div class="mb-4">
            <label for="mobile_no" class="block mb-2 font-medium">Visitor Mobile Number</label>
            <input type="text" name="mobile_no" id="mobile_no" value="{{ old('mobile_no', $visitor->mobile_no) }}"
                class="border border-gray-300 p-2 rounded w-full" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Update Visitor
        </button>
        <a href="{{ route('visitors.index') }}" class="ml-2 text-gray-500 hover:underline">Back</a>
    </form>
@endsection

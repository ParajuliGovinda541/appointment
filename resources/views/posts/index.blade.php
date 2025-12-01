@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Posts</h1>

    <a href="{{ route('posts.create') }}" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Add Post
    </a>

    <!-- Posts Table -->
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($posts as $post)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $post->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $post->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($post->status == 'Active')
                                <span
                                    class="px-2 py-1 rounded bg-green-200 text-green-800 text-sm">{{ $post->status }}</span>
                            @else
                                <span class="px-2 py-1 rounded bg-gray-200 text-gray-800 text-sm">{{ $post->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                            <a href="{{ route('posts.edit', $post->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">Edit</a>

                            @if ($post->status == 'Active')
                                <form action="{{ route('posts.deactivate', $post->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                        Deactivate
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('posts.activate', $post->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">
                                        Activate
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No posts found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

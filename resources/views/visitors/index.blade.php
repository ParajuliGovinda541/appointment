@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Visitors</h1>

    <a href="{{ route('visitors.create') }}" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Add visitor
    </a>

    <!-- visitors Table -->
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile Number
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($visitors as $visitor)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $visitor->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $visitor->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $visitor->mobile_no }}</td>

                        <td class="px-6 py-4 whitespace-nowrap">{{ $visitor->email }}</td>


                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($visitor->status == 'Active')
                                <span
                                    class="px-2 py-1 rounded bg-green-200 text-green-800 text-sm">{{ $visitor->status }}</span>
                            @else
                                <span
                                    class="px-2 py-1 rounded bg-gray-200 text-gray-800 text-sm">{{ $visitor->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                            <a href="{{ route('visitors.edit', $visitor->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">Edit</a>

                            <form action="{{ route('visitors.activate', $visitor->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">
                                    Activate
                                </button>
                            </form>

                            <form action="{{ route('visitors.deactivate', $visitor->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    Deactivate
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No visitors found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

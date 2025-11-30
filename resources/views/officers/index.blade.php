@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Officers</h1>

    <a href="{{ route('officers.create') }}" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Add Officers
    </a>

    <!-- officerss Table -->
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Start
                        Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work End Time
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post</th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($officers as $officer)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $officer->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $officer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $officer->work_start_time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $officer->work_end_time }}</td>

                        <td class="px-6 py-4 whitespace-nowrap">{{ $officer->post->name }}</td>


                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($officer->status == 'Active')
                                <span
                                    class="px-2 py-1 rounded bg-green-200 text-green-800 text-sm">{{ $officer->status }}</span>
                            @else
                                <span
                                    class="px-2 py-1 rounded bg-gray-200 text-gray-800 text-sm">{{ $officer->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                            <a href="{{ route('officers.edit', $officer->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">Edit</a>
                            {{-- <a href="{{ route('officers.appointment', $officer->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                View Appointments
                            </a> --}}

                            @if ($officer->status == 'Active')
                                <form action="{{ route('officers.deactivate', $officer->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                        Deactivate
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('officers.activate', $officer->id) }}" method="officers">
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
                            No officerss found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Appointments</h1>

    <a href="{{ route('appointments.create') }}"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4 inline-block">
        Add Appointment
    </a>

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment
                        Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Officer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visitor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($appointments as $appointment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->officer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->visitor->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->start_time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->end_time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Active' => 'bg-green-200 text-green-800',
                                    'Cancelled' => 'bg-red-200 text-red-800',
                                    'Deactivated' => 'bg-gray-200 text-gray-800',
                                    'Completed' => 'bg-blue-200 text-blue-800',
                                ];
                            @endphp
                            <span
                                class="px-2 py-1 rounded text-sm {{ $statusColors[$appointment->status] ?? 'bg-gray-200 text-gray-800' }}">
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                            <a href="{{ route('appointments.edit', $appointment->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                Edit
                            </a>

                            @if ($appointment->status !== 'Cancelled')
                                <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            No appointments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Activities</h1>

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Officer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date &
                        Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date &
                        Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($activities as $activity)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $activity->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $activity->officer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $activity->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $activity->start_date }} {{ $activity->start_time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $activity->end_date }} {{ $activity->end_time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($activity->status === 'Active')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Cancelled
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                            <a href="{{ route('activitys.edit', $activity->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>

                            @if ($activity->status === 'Active')
                                <form action="{{ route('activitys.deactivate', $activity->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Deactivate
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('activitys.activate', $activity->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                        Activate
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No activities found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Activities</h1>
            <a href="{{ route('activitys.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
                Add Activity
            </a>
        </div>

        {{-- Filter Form --}}
        <form method="GET"
            class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 p-4 bg-white shadow rounded">
            <div>
                <label class="block text-sm font-medium">Type</label>
                <input type="text" name="type" value="{{ $filters['type'] ?? '' }}" class="border p-2 rounded w-full">
            </div>
            <div>
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="border p-2 rounded w-full">
                    <option value="">All</option>
                    <option value="Active" @if (($filters['status'] ?? '') == 'Active') selected @endif>Active</option>
                    <option value="Cancelled" @if (($filters['status'] ?? '') == 'Cancelled') selected @endif>Cancelled</option>
                    <option value="Completed" @if (($filters['status'] ?? '') == 'Completed') selected @endif>Completed</option>

                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Officer</label>
                <select name="officer_id" class="border p-2 rounded w-full">
                    <option value="">All</option>
                    @foreach ($officers as $officer)
                        <option value="{{ $officer->id }}" @if (($filters['officer_id'] ?? '') == $officer->id) selected @endif>
                            {{ $officer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Visitor</label>
                <select name="visitor_id" class="border p-2 rounded w-full">
                    <option value="">All</option>
                    @foreach ($visitors as $visitor)
                        <option value="{{ $visitor->id }}" @if (($filters['visitor_id'] ?? '') == $visitor->id) selected @endif>
                            {{ $visitor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Start Date</label>
                <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}"
                    class="border p-2 rounded w-full">
            </div>
            <div>
                <label class="block text-sm font-medium">End Date</label>
                <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}"
                    class="border p-2 rounded w-full">
            </div>
            <div>
                <label class="block text-sm font-medium">Start Time</label>
                <input type="time" name="start_time" value="{{ $filters['start_time'] ?? '' }}"
                    class="border p-2 rounded w-full">
            </div>
            <div>
                <label class="block text-sm font-medium">End Time</label>
                <input type="time" name="end_time" value="{{ $filters['end_time'] ?? '' }}"
                    class="border p-2 rounded w-full">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
                    Filter
                </button>
            </div>
        </form>

        {{-- Activities Table --}}
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Officer</th>
                        <th class="px-6 py-3 text-left">Type</th>
                        <th class="px-6 py-3 text-left">Start</th>
                        <th class="px-6 py-3 text-left">End</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $activity->id }}</td>
                            <td class="px-6 py-3">{{ $activity->officer->name }}</td>
                            <td class="px-6 py-3 capitalize">{{ $activity->type }}</td>
                            <td class="px-6 py-3">{{ $activity->start_date }} {{ $activity->start_time }}</td>
                            <td class="px-6 py-3">{{ $activity->end_date }} {{ $activity->end_time }}</td>
                            <td class="px-6 py-3">
                                @php
                                    $now = now();
                                    $end = \Carbon\Carbon::parse($activity->end_date . ' ' . $activity->end_time);
                                @endphp
                                <span
                                    class="px-2 inline-flex text-xs font-semibold rounded-full
                                {{ $end->lt($now) && $activity->status === 'Active' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $end->lt($now) && $activity->status !== 'Active' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $end->gte($now) && $activity->status === 'Active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $end->gte($now) && $activity->status !== 'Active' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $end->lt($now) && $activity->status === 'Active' ? 'Completed' : $activity->status }}
                                </span>
                            </td>
                            <td class="px-6 py-3 flex space-x-2">
                                <a href="{{ route('activitys.edit', $activity->id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-sm">
                                    Edit
                                </a>
                                @if ($activity->status === 'Active')
                                    <form action="{{ route('activitys.deactivate', $activity->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm">
                                            Deactivate
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('activitys.activate', $activity->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-sm">
                                            Activate
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No activities found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

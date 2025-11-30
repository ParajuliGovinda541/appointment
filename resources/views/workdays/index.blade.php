@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Workdays</h1>

    <a href="{{ route('workdays.create') }}" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Add Workdays
    </a>

    <!-- workdays Table -->
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Officer Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day Name</th>

                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($workdays as $workday)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $workday->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $workday->officer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $workday->day_of_week }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No workdays found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

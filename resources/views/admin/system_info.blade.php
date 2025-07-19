@extends('layouts.app')

@section('title', 'System Info')
@section('page-title', 'System Information')

@section('content')
    <div class="bg-white rounded-2xl shadow p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-900">System Information</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Component</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Value</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($systemInfo as $key => $value)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $key }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $value }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

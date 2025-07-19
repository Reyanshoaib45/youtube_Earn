@extends('layouts.app')

@section('title', 'Referral Settings')
@section('page-title', 'Referral System Settings')

@section('content')
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-bold mb-4">Referral Bonus per Package</h2>

        <table class="min-w-full divide-y divide-gray-200 mb-6">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Package</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Referral Bonus</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($packages as $pkg)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $pkg->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">
                        ${{ number_format($pkg->referral_bonus, 2) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- You can add a form here to update referral bonuses if needed --}}
    </div>
@endsection

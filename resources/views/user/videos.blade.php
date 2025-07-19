@extends('layouts.app')

@section('title', 'Videos')
@section('page-title', 'Videos')

@section('content')
<div class="space-y-6">
    <!-- Header with package info -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Available Videos</h1>
                <p class="mt-2 text-gray-600">Watch videos and earn rewards</p>
            </div>
            <div class="mt-4 lg:mt-0">
                <div class="bg-blue-50 rounded-2xl p-4">
                    <div class="flex items-center space-x-4 text-sm">
                        <div>
                            <span class="text-gray-600">Package:</span>
                            <span class="font-semibold text-blue-700">{{ $activePackage->package->name }}</span>
                        </div>
                        <div class="hidden sm:block w-px h-4 bg-gray-300"></div>
                        <div>
                            <span class="text-gray-600">Expires:</span>
                            <span class="font-semibold text-red-600">{{ $activePackage->end_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($videos->isEmpty())
        <!-- No videos state -->
        <div class="bg-white rounded-2xl shadow-md p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-video text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Videos Available</h3>
            <p class="text-gray-600 mb-6">You have watched all available videos or no videos have been uploaded yet.</p>
            <a href="{{ route('user.dashboard') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    @else
        <!-- Videos grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($videos as $video)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 hover:scale-105">
                    <!-- Video thumbnail -->
                    <div class="relative aspect-video bg-gray-200">
                        <img src="https://img.youtube.com/vi/{{ preg_replace('/.*(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+).*/', '$1', $video->youtube_link) }}/maxresdefault.jpg" 
                             alt="{{ $video->title }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                            <div class="w-16 h-16 bg-white bg-opacity-90 rounded-full flex items-center justify-center">
                                <i class="fas fa-play text-blue-600 text-xl ml-1"></i>
                            </div>
                        </div>
                        <!-- Duration badge -->
                        <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                            {{ $video->min_watch_minutes }} min
                        </div>
                    </div>
                    
                    <!-- Video info -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $video->title }}</h3>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock mr-1"></i>
                                <span>{{ $video->min_watch_minutes }} min watch time</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-coins text-yellow-500 mr-1"></i>
                                <span class="font-semibold text-green-600">${{ number_format($video->reward_amount, 2) }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('user.video.watch', $video) }}" 
                           class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center py-3 px-4 rounded-2xl font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-play mr-2"></i>Watch Video
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

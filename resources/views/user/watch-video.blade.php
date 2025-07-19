@extends('layouts.app')

@section('title', 'Watch Video')
@section('page-title', 'Watch Video')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Video player card -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <!-- Video header -->
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $video->title }}</h1>
            <div class="flex items-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <i class="fas fa-clock mr-1"></i>
                    <span>{{ $video->min_watch_minutes }} min required</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-coins mr-1 text-yellow-500"></i>
                    <span class="font-semibold text-green-600">${{ number_format($video->reward_amount, 2) }} reward</span>
                </div>
            </div>
        </div>

        <!-- Video player -->
        <div class="relative">
            <div class="aspect-video">
                <iframe 
                    id="youtube-player"
                    src="{{ $video->getYoutubeEmbedUrl() }}?enablejsapi=1&origin={{ url('/') }}"
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen
                    class="w-full h-full">
                </iframe>
            </div>
        </div>

        <!-- Progress section -->
        <div class="p-6">
            <div class="bg-gray-50 rounded-2xl p-4">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-medium text-gray-700">
                        <i class="fas fa-stopwatch mr-1"></i>Watch Progress
                    </span>
                    <span id="timer" class="text-sm font-bold text-blue-600">00:00 / {{ $video->min_watch_minutes }}:00</span>
                </div>
                
                <!-- Progress bar -->
                <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                    <div id="progress-bar" 
                         class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-300" 
                         style="width: 0%"></div>
                </div>
                
                <div class="flex items-center justify-between text-xs text-gray-600">
                    <span>
                        <i class="fas fa-info-circle mr-1"></i>
                        Watch at least {{ $video->min_watch_minutes }} minutes to earn your reward
                    </span>
                    <span id="status-text" class="font-medium">Watching...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Reward notification -->
    <div id="reward-notification" 
         class="hidden bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-2xl p-6 shadow-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-trophy text-2xl"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-xl font-bold mb-1">Congratulations! 🎉</h3>
                <p class="text-green-100">You've earned <span id="reward-amount" class="font-bold">$0.00</span> for watching this video!</p>
            </div>
            <div class="ml-4">
                <a href="{{ route('user.videos') }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-2xl transition-colors">
                    <i class="fas fa-arrow-right mr-1"></i>Next Video
                </a>
            </div>
        </div>
    </div>

    <!-- Video details -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Video Details</h2>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-clock w-4 mr-2"></i>
                        <span>Minimum watch time: {{ $video->min_watch_minutes }} minutes</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-coins w-4 mr-2 text-yellow-500"></i>
                        <span>Reward amount: ${{ number_format($video->reward_amount, 2) }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-user w-4 mr-2"></i>
                        <span>Uploaded by: {{ $video->creator->name }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 lg:mt-0">
                <a href="{{ route('user.videos') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-2xl hover:bg-gray-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Videos
                </a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let watchedSeconds = {{ $watchedVideo->watched_seconds }};
    let minWatchSeconds = {{ $video->min_watch_minutes * 60 }};
    let isCompleted = {{ $watchedVideo->is_completed ? 'true' : 'false' }};
    let timer;
    
    if (!isCompleted) {
        timer = setInterval(function() {
            watchedSeconds++;
            updateProgress();
            
            // Update progress every 10 seconds
            if (watchedSeconds % 10 === 0) {
                updateWatchProgress();
            }
        }, 1000);
    } else {
        updateProgress();
        showCompletedState();
    }
    
    function updateProgress() {
        let minutes = Math.floor(watchedSeconds / 60);
        let seconds = watchedSeconds % 60;
        let minMinutes = Math.floor(minWatchSeconds / 60);
        
        $('#timer').text(
            String(minutes).padStart(2, '0') + ':' + 
            String(seconds).padStart(2, '0') + ' / ' + 
            String(minMinutes).padStart(2, '0') + ':00'
        );
        
        let progressPercent = Math.min((watchedSeconds / minWatchSeconds) * 100, 100);
        $('#progress-bar').css('width', progressPercent + '%');
        
        if (progressPercent >= 100 && !isCompleted) {
            $('#progress-bar').removeClass('from-blue-500 to-purple-600').addClass('from-green-500 to-emerald-600');
            $('#status-text').text('Completed!').addClass('text-green-600');
        } else if (progressPercent >= 100) {
            $('#progress-bar').removeClass('from-blue-500 to-purple-600').addClass('from-green-500 to-emerald-600');
            $('#status-text').text('Completed!').addClass('text-green-600');
        }
    }
    
    function updateWatchProgress() {
        $.ajax({
            url: '{{ route("user.video.progress", $video) }}',
            method: 'POST',
            data: {
                watched_seconds: watchedSeconds,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.completed && !isCompleted) {
                    isCompleted = true;
                    clearInterval(timer);
                    showRewardNotification(response.reward);
                }
            },
            error: function() {
                console.log('Error updating progress');
            }
        });
    }
    
    function showRewardNotification(reward) {
        $('#reward-amount').text('$' + parseFloat(reward).toFixed(2));
        $('#reward-notification').removeClass('hidden').hide().fadeIn(500);
        $('#progress-bar').removeClass('from-blue-500 to-purple-600').addClass('from-green-500 to-emerald-600');
        $('#status-text').text('Completed!').addClass('text-green-600');
        
        // Scroll to notification
        $('html, body').animate({
            scrollTop: $('#reward-notification').offset().top - 100
        }, 500);
        
        // Show success toast
        showToast('Congratulations! You earned $' + parseFloat(reward).toFixed(2), 'success');
    }
    
    function showCompletedState() {
        $('#progress-bar').removeClass('from-blue-500 to-purple-600').addClass('from-green-500 to-emerald-600');
        $('#status-text').text('Completed!').addClass('text-green-600');
        $('#reward-notification').removeClass('hidden');
        $('#reward-amount').text('${{ number_format($video->reward_amount, 2) }}');
    }
    
    // Handle page visibility change
    $(document).on('visibilitychange', function() {
        if (document.hidden && timer) {
            clearInterval(timer);
        } else if (!document.hidden && !isCompleted) {
            timer = setInterval(function() {
                watchedSeconds++;
                updateProgress();
                
                if (watchedSeconds % 10 === 0) {
                    updateWatchProgress();
                }
            }, 1000);
        }
    });
    
    // Handle page unload
    $(window).on('beforeunload', function() {
        if (!isCompleted) {
            updateWatchProgress();
        }
    });
});
</script>
@endsection

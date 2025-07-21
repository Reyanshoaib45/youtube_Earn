@extends('layouts.app')

@section('title', 'Watch Videos - Watch & Earn')

@push('scripts')
<script>
    let watchingSessions = {};
    let tabVisible = true;
    let originalTitle = document.title;
    let tabSwitchCount = 0;
    let maxTabSwitches = 2;

    // Enhanced Anti-cheat System
    document.addEventListener('visibilitychange', function() {
        tabVisible = !document.hidden;
        
        if (!tabVisible) {
            tabSwitchCount++;
            
            Object.keys(watchingSessions).forEach(videoId => {
                if (watchingSessions[videoId].active) {
                    clearInterval(watchingSessions[videoId].interval);
                    watchingSessions[videoId].active = false;
                    watchingSessions[videoId].pausedTime = Date.now();
                }
            });
            
            document.title = `⚠️ Return to continue! (${tabSwitchCount}/${maxTabSwitches})`;
            
            if (tabSwitchCount > maxTabSwitches) {
                Object.keys(watchingSessions).forEach(videoId => {
                    if (watchingSessions[videoId] && !watchingSessions[videoId].completed) {
                        failWatchSession(videoId, 'Too many tab switches');
                    }
                });
            }
        } else {
            document.title = originalTitle;
            
            if (tabSwitchCount <= maxTabSwitches) {
                Object.keys(watchingSessions).forEach(videoId => {
                    if (watchingSessions[videoId] && !watchingSessions[videoId].completed) {
                        resumeWatching(videoId);
                    }
                });
            }
        }
    });

    function startWatching(videoId, duration) {
        if (!tabVisible) {
            alert('Please stay on this tab while watching videos!');
            return;
        }

        if (tabSwitchCount > maxTabSwitches) {
            alert('Too many tab switches detected. Please refresh the page to try again.');
            return;
        }

        const button = document.querySelector(`[data-video-id="${videoId}"]`);
        const progressDiv = document.getElementById(`progress-${videoId}`);
        const progressBar = progressDiv.querySelector('.bg-blue-600');
        const timer = document.getElementById(`timer-${videoId}`);

        button.disabled = true;
        button.textContent = 'Watching...';
        progressDiv.classList.remove('hidden');

        tabSwitchCount = 0;

        watchingSessions[videoId] = {
            startTime: Date.now(),
            duration: duration,
            elapsed: 0,
            active: true,
            completed: false,
            interval: null,
            pausedTime: null,
            totalPausedTime: 0
        };
        
        resumeWatching(videoId);
    }

    function resumeWatching(videoId) {
        if (!watchingSessions[videoId] || watchingSessions[videoId].completed) return;

        const session = watchingSessions[videoId];
        const progressBar = document.querySelector(`#progress-${videoId} .bg-blue-600`);
        const timer = document.getElementById(`timer-${videoId}`);

        if (session.pausedTime) {
            session.totalPausedTime += Date.now() - session.pausedTime;
            session.pausedTime = null;
        }

        session.active = true;
        session.interval = setInterval(() => {
            if (!tabVisible) {
                clearInterval(session.interval);
                session.active = false;
                return;
            }

            session.elapsed++;
            const progress = (session.elapsed / session.duration) * 100;
            
            progressBar.style.width = progress + '%';
            timer.textContent = session.elapsed;

            if (session.elapsed >= session.duration * 0.8) {
                completeWatching(videoId);
            }
        }, 1000);
    }

    function completeWatching(videoId) {
        const session = watchingSessions[videoId];
        clearInterval(session.interval);
        session.completed = true;

        $.ajax({
            url: '{{ route("user.watch-video") }}',
            method: 'POST',
            data: {
                video_id: videoId,
                watch_duration: session.elapsed,
                total_paused_time: session.totalPausedTime,
                tab_switches: tabSwitchCount,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    const button = document.querySelector(`[data-video-id="${videoId}"]`);
                    button.textContent = `Earned Rs. ${response.reward}!`;
                    button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    button.classList.add('bg-green-600');
                    
                    showSuccessMessage(`Congratulations! You earned Rs. ${response.reward}. New balance: Rs. ${response.new_balance}`);
                    
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                }
            },
            error: function(xhr) {
                const response = JSON.parse(xhr.responseText);
                alert(response.error || 'An error occurred');
                location.reload();
            }
        });
    }

    function failWatchSession(videoId, reason) {
        const session = watchingSessions[videoId];
        if (session) {
            clearInterval(session.interval);
            session.completed = true;
            
            const button = document.querySelector(`[data-video-id="${videoId}"]`);
            button.textContent = 'Failed';
            button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            button.classList.add('bg-red-600');
            button.disabled = true;
            
            alert(`Watch session failed: ${reason}`);
        }
    }

    function showSuccessMessage(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        successDiv.textContent = message;
        document.body.appendChild(successDiv);
        
        setTimeout(() => {
            successDiv.remove();
        }, 5000);
    }

    // Security measures
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' || 
            (e.ctrlKey && e.shiftKey && e.key === 'I') ||
            (e.ctrlKey && e.key === 'u') ||
            (e.ctrlKey && e.shiftKey && e.key === 'C')) {
            e.preventDefault();
            alert('Developer tools are disabled during video watching!');
        }
    });
</script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Watch Videos & Earn</h1>
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-100 p-4 rounded-lg">
                <h3 class="font-semibold text-blue-800">Today's Progress</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $todayWatched }}/{{ $currentPackage->daily_video_limit }}</p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg">
                <h3 class="font-semibold text-green-800">Package</h3>
                <p class="text-lg font-bold text-green-600">{{ $currentPackage->name }}</p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-lg">
                <h3 class="font-semibold text-yellow-800">Status</h3>
                <p class="text-lg font-bold {{ $canWatch ? 'text-green-600' : 'text-red-600' }}">
                    {{ $canWatch ? 'Can Watch' : 'Limit Reached' }}
                </p>
            </div>
        </div>
    </div>

    @if($canWatch)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($videos as $video)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="aspect-video">
                    <iframe id="video-{{ $video->id }}" 
                            src="https://www.youtube.com/embed/{{ $video->youtube_id }}?enablejsapi=1&rel=0&modestbranding=1&controls=0" 
                            frameborder="0" 
                            class="w-full h-full"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                    </iframe>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">{{ $video->title }}</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-green-600 font-bold">Reward: Rs. {{ $video->reward }}</span>
                        <button onclick="startWatching({{ $video->id }}, {{ $video->duration }})" 
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 watch-btn"
                                data-video-id="{{ $video->id }}">
                            Watch & Earn
                        </button>
                    </div>
                    <div id="progress-{{ $video->id }}" class="mt-2 hidden">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-1000" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Watching... <span id="timer-{{ $video->id }}">0</span>s / {{ $video->duration }}s</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">No videos available at the moment.</p>
            </div>
            @endforelse
        </div>
    @else
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <p class="font-bold">Daily limit reached!</p>
            <p>You have watched all videos for today. Come back tomorrow to earn more!</p>
        </div>
    @endif
</div>
@endsection

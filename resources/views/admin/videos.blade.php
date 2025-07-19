@extends('layouts.app')

@section('title', 'Admin Manage Videos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Admin Manage Videos</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <form action="{{ route('admin.videos') }}" method="GET" class="flex items-center space-x-4">
            <input type="text" name="search" placeholder="Search videos..."
                   class="form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                   value="{{ request('search') }}">
            <select name="category" class="form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">
                Filter
            </button>
        </form>
        <button onclick="openAddVideoModal()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md">
            Add New Video
        </button>
    </div>

    @if ($videos->isEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">No videos found.</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Reward
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Min Watch
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Active
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($videos as $video)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $video->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $video->category->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ${{ number_format($video->reward_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $video->min_watch_minutes }} min
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($video->status == 'published') bg-green-100 text-green-800
                                        @elseif($video->status == 'draft') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($video->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if($video->is_active)
                                        <span class="text-green-500">&#10004;</span>
                                    @else
                                        <span class="text-red-500">&#10006;</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="openEditVideoModal({{ $video->id }}, '{{ $video->title }}', '{{ $video->description }}', '{{ $video->video_url }}', '{{ $video->thumbnail ? Storage::url($video->thumbnail) : '' }}', {{ $video->category_id }}, {{ $video->reward_amount }}, {{ $video->min_watch_minutes }}, '{{ $video->difficulty_level }}', '{{ $video->status }}', {{ $video->is_active ? 'true' : 'false' }})"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-3">
                                        Edit
                                    </button>
                                    <button onclick="openDeleteVideoModal({{ $video->id }}, '{{ $video->title }}')"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $videos->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Add Video Modal -->
<div id="addVideoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Add New Video</h3>
        <form action="{{ route('admin.video.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="add_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="add_title" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="add_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="add_description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
            </div>
            <div class="mb-4">
                <label for="add_video_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Video URL</label>
                <input type="url" name="video_url" id="add_video_url" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="add_thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thumbnail</label>
                <input type="file" name="thumbnail" id="add_thumbnail"
                       class="mt-1 block w-full text-gray-700 dark:text-gray-300">
            </div>
            <div class="mb-4">
                <label for="add_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select name="category_id" id="add_category_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="add_reward_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reward Amount ($)</label>
                <input type="number" name="reward_amount" id="add_reward_amount" step="0.01" min="0" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="add_min_watch_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Watch Minutes</label>
                <input type="number" name="min_watch_minutes" id="add_min_watch_minutes" min="1" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="add_difficulty_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Difficulty Level</label>
                <select name="difficulty_level" id="add_difficulty_level" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="add_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="add_status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_active" id="add_is_active" value="1" checked
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                <label for="add_is_active" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Is Active</label>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeAddVideoModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Add Video</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Video Modal -->
<div id="editVideoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Edit Video</h3>
        <form id="editVideoForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="video_id" id="edit_video_id">
            <div class="mb-4">
                <label for="edit_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="edit_title" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="edit_description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
            </div>
            <div class="mb-4">
                <label for="edit_video_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Video URL</label>
                <input type="url" name="video_url" id="edit_video_url" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="edit_thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thumbnail</label>
                <input type="file" name="thumbnail" id="edit_thumbnail"
                       class="mt-1 block w-full text-gray-700 dark:text-gray-300">
                <img id="current_thumbnail" src="/placeholder.svg" alt="Current Thumbnail" class="mt-2 max-h-24 hidden">
            </div>
            <div class="mb-4">
                <label for="edit_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select name="category_id" id="edit_category_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="edit_reward_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reward Amount ($)</label>
                <input type="number" name="reward_amount" id="edit_reward_amount" step="0.01" min="0" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="edit_min_watch_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Watch Minutes</label>
                <input type="number" name="min_watch_minutes" id="edit_min_watch_minutes" min="1" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="edit_difficulty_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Difficulty Level</label>
                <select name="difficulty_level" id="edit_difficulty_level" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="edit_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="edit_status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                <label for="edit_is_active" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Is Active</label>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeEditVideoModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Update Video</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Video Modal -->
<div id="deleteVideoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Delete Video</h3>
        <p class="text-gray-700 dark:text-gray-300 mb-4">Are you sure you want to delete "<span id="delete_video_title" class="font-semibold"></span>"? This action cannot be undone.</p>
        <form id="deleteVideoForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeDeleteVideoModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</button>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddVideoModal() {
        document.getElementById('addVideoModal').classList.remove('hidden');
    }

    function closeAddVideoModal() {
        document.getElementById('addVideoModal').classList.add('hidden');
    }

    function openEditVideoModal(id, title, description, videoUrl, thumbnailUrl, categoryId, rewardAmount, minWatchMinutes, difficultyLevel, status, isActive) {
        document.getElementById('edit_video_id').value = id;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_video_url').value = videoUrl;
        document.getElementById('edit_category_id').value = categoryId;
        document.getElementById('edit_reward_amount').value = rewardAmount;
        document.getElementById('edit_min_watch_minutes').value = minWatchMinutes;
        document.getElementById('edit_difficulty_level').value = difficultyLevel;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_is_active').checked = isActive;

        const currentThumbnail = document.getElementById('current_thumbnail');
        if (thumbnailUrl) {
            currentThumbnail.src = thumbnailUrl;
            currentThumbnail.classList.remove('hidden');
        } else {
            currentThumbnail.classList.add('hidden');
            currentThumbnail.src = '';
        }

        document.getElementById('editVideoForm').action = `/admin/videos/${id}`;
        document.getElementById('editVideoModal').classList.remove('hidden');
    }

    function closeEditVideoModal() {
        document.getElementById('editVideoModal').classList.add('hidden');
    }

    function openDeleteVideoModal(id, title) {
        document.getElementById('delete_video_title').innerText = title;
        document.getElementById('deleteVideoForm').action = `/admin/videos/${id}`;
        document.getElementById('deleteVideoModal').classList.remove('hidden');
    }

    function closeDeleteVideoModal() {
        document.getElementById('deleteVideoModal').classList.add('hidden');
    }
</script>
@endsection

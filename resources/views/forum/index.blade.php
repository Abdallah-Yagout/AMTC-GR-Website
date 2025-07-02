<x-app-layout>
    <!-- Hero Section - Responsive -->
    <section class="bg-primary-200 px-4 md:px-10 lg:px-40 py-6 md:py-8">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 md:mb-2">{{__('Welcome to Our Forums')}}</h2>
        <p class="text-sm md:text-base text-white">{{__('Ask your questions, get expert support, and collaborate on meaningful topics.')}}</p>
    </section>

    <!-- Main Content - Responsive Container -->
    <div class="bg-black text-white py-6 md:py-10 px-4 md:px-8 lg:px-16 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Tabs - Responsive -->
            <div class="flex flex-wrap items-center gap-2 md:gap-4 border-b border-gray-700 mb-4 md:mb-6">
                <button onclick="switchTab('popular')" class="tab-button text-sm md:text-base cursor-pointer pb-2 border-b-2 {{ $activeTab === 'popular' ? 'border-red-600 text-white' : 'border-transparent text-gray-400' }} font-medium" data-tab="popular">Popular</button>
                <button onclick="switchTab('newest')" class="tab-button text-sm md:text-base cursor-pointer pb-2 border-b-2 {{ $activeTab === 'newest' ? 'border-red-600 text-white' : 'border-transparent text-gray-400' }} font-medium" data-tab="newest">Newest</button>
                <button onclick="openPostModal()" class="bg-red-600 cursor-pointer hover:bg-red-700 text-white px-4 py-2 rounded-lg mb-3">Post</button>
            </div>

            <!-- Main Content Grid -->
            <div class="flex flex-col lg:flex-row gap-6 md:gap-8 lg:gap-10">
                <!-- Forum Posts - Responsive Width -->
                <div class="w-full lg:flex-1 space-y-3 md:space-y-4">
                    <!-- Popular Posts -->
                    <div id="popular-tab" class="tab-content" style="{{ $activeTab !== 'popular' ? 'display: none;' : '' }}">
                        @foreach ($popularForums as $forum)
                            <div class="forum-post mb-3 bg-zinc-800 p-4 md:p-6 rounded" data-forum-id="{{ $forum->id }}">
                                <div class="flex flex-col sm:flex-row justify-between items-start gap-3 md:gap-4">
                                    <!-- Forum Content -->
                                    <a href="{{route('forum.show',$forum->slug)}}">
                                        <div class="flex-1">
                                            <h4 class="text-base md:text-lg font-semibold text-white">{{ $forum->title }}</h4>
                                            <p class="text-xs text-gray-500 mt-1 md:mt-2">
                                                Posted by {{ $forum->user->name }} • {{ $forum->upvotes_count }} upvotes
                                            </p>
                                        </div>
                                    </a>
                                    <!-- Action Buttons -->
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if(auth()->id() === $forum->user_id)
                                            <div class="relative">
                                                <button class="dropdown-toggle flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-md p-1 transition-colors duration-200 hover:bg-gray-700/50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-zinc-700 rounded-md shadow-lg z-50 py-1">
                                                    <a href="#" class="edit-option block px-4 py-2 text-sm text-white hover:bg-zinc-600 flex items-center" data-forum-id="{{ $forum->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <a href="#" class="delete-option block px-4 py-2 text-sm text-white hover:bg-zinc-600 flex items-center" data-forum-id="{{ $forum->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <button class="upvote-btn flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-md p-1 transition-colors duration-200 border {{ $forum->upvotedByMe ? 'border-red-600' : 'border-gray-500' }}"
                                                data-forum-id="{{ $forum->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4 md:h-5 md:w-5 {{ $forum->upvotedByMe ? 'text-red-500' : 'text-gray-500' }} hover:text-primary-600"
                                                 viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                      d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <span class="text-xs upvote-count {{ $forum->upvotedByMe ? 'text-red-500' : 'text-gray-400' }} md:text-sm" data-forum-id="{{ $forum->id }}">{{ $forum->upvotes_count }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-6">
                            {{ $popularForums->appends(['tab' => 'popular'])->links() }}
                        </div>
                    </div>

                    <!-- Newest Posts -->
                    <div id="newest-tab" class="tab-content" style="{{ $activeTab !== 'newest' ? 'display: none;' : '' }}">
                        @foreach ($newestForums as $forum)
                            <div class="forum-post mb-3 bg-zinc-800 p-4 md:p-6 rounded" data-forum-id="{{ $forum->id }}">
                                <div class="flex flex-col sm:flex-row justify-between items-start gap-3 md:gap-4">
                                    <a href="{{route('forum.show',$forum->slug)}}">
                                        <div class="flex-1">
                                            <h4 class="text-base md:text-lg font-semibold text-white">{{ $forum->title }}</h4>
                                            <p class="text-xs text-gray-500 mt-1 md:mt-2">
                                                Posted by {{ $forum->user->name }} • {{ $forum->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </a>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if(auth()->id() === $forum->user_id)
                                            <div class="relative">
                                                <button class="dropdown-toggle flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-md p-1 transition-colors duration-200 hover:bg-gray-700/50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-zinc-700 rounded-md shadow-lg z-50 py-1">
                                                    <a href="#" class="edit-option block px-4 py-2 text-sm text-white hover:bg-zinc-600 flex items-center" data-forum-id="{{ $forum->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <a href="#" class="delete-option block px-4 py-2 text-sm text-white hover:bg-zinc-600 flex items-center" data-forum-id="{{ $forum->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <button class="upvote-btn flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-md p-1 transition-colors duration-200 border {{ $forum->upvotedByMe ? 'border-red-600' : 'border-gray-500' }}"
                                                data-forum-id="{{ $forum->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4 md:h-5 md:w-5 {{ $forum->upvotedByMe ? 'text-red-500' : 'text-gray-500' }} hover:text-primary-600"
                                                 viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                      d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <span class="text-xs upvote-count {{ $forum->upvotedByMe ? 'text-red-500' : 'text-gray-400' }} md:text-sm" data-forum-id="{{ $forum->id }}">{{ $forum->upvotes_count }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-6">
                            {{ $newestForums->appends(['tab' => 'newest'])->links() }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Responsive -->
                <div class="w-full border-l border-[#454545] lg:w-3/12 space-y-6">
                    <!-- New Discussions -->
                    <section class="p-6 text-white">
                        <h2 class="text-lg font-bold mb-4 border-b border-gray-700 pb-2">New Discussions</h2>
                        <div class="space-y-4">
                            @foreach($recentDiscussions as $discussion)
                                <div class="flex flex-col p-3 rounded transition">
                                    <!-- First line: Image and first name -->
                                    <div class="flex items-center gap-3 mb-2">
                                        <img class="w-8 h-8 rounded-full object-cover"
                                             src="{{ asset('storage/'.$discussion->user->profile_photo_path) }}"
                                             alt="{{ $discussion->user->name }}">
                                        <span class="font-medium text-sm">
                                            {{ explode(' ', $discussion->user->name)[0] }}
                                        </span>
                                    </div>

                                    <!-- Second line: Discussion title -->
                                    <a href="{{ route('forum.show', $discussion->slug) }}"
                                       class="text-white hover:underline mb-1">
                                        <h3 class="font-medium text-base line-clamp-2">{{ $discussion->title }}</h3>
                                    </a>

                                    <!-- Third line: Date and comments count -->
                                    <div class="flex items-center gap-2 text-xs text-gray-400">
                                        <span>{{\Illuminate\Support\Carbon::parse($discussion->created_at)->format('j F')}}</span>
                                        <span>•</span>
                                        <span>{{ $discussion->comments_count }} comments</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <!-- Popular Posts -->
                    <section class="rounded-lg p-6 text-white">
                        <h2 class="text-lg font-bold mb-4 border-b border-gray-700 pb-2">Popular Posts</h2>
                        <div class="space-y-4">
                            @foreach($popularForums as $post)
                                <div class="flex flex-col p-3 rounded transition">
                                    <!-- First line: Image and first name -->
                                    <div class="flex items-center gap-3 mb-2">
                                        <img class="w-8 h-8 rounded-full object-cover"
                                             src="{{ asset('storage/'.$post->user->profile_photo_path) }}"
                                             alt="{{ $post->user->name }}">
                                        <span class="font-medium text-sm">
                                            {{ explode(' ', $post->user->name)[0] }}
                                        </span>
                                    </div>

                                    <!-- Second line: Discussion title -->
                                    <a href="{{ route('forum.show', $post->slug) }}"
                                       class="text-white hover:underline mb-1">
                                        <h3 class="font-medium text-base line-clamp-2">{{ $post->title }}</h3>
                                    </a>

                                    <!-- Third line: Date and comments count -->
                                    <div class="flex items-center gap-2 text-xs text-gray-400">
                                        <span>{{\Illuminate\Support\Carbon::parse($post->created_at)->format('j F')}}</span>
                                        <span>•</span>
                                        <span>{{ $post->upvotes_count }} votes</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>

            <!-- Bottom Section - Responsive Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mt-8 md:mt-12">
                <div class="bg-zinc-800 p-4 md:p-6 rounded">
                    <h4 class="text-base md:text-lg font-semibold mb-1 md:mb-2">News & Updates</h4>
                    <p class="text-xs md:text-sm text-gray-400">Check out the latest news and updates from the community.</p>
                    <a href="#" class="text-red-500 text-xs md:text-sm mt-1 md:mt-2 inline-block hover:underline">View all updates</a>
                </div>
                <div class="bg-zinc-800 p-4 md:p-6 rounded">
                    <h4 class="text-base md:text-lg font-semibold mb-1 md:mb-2">Support</h4>
                    <p class="text-xs md:text-sm text-gray-400">Feel free to contact us, we're here to help you.</p>
                    <a href="#" class="text-red-500 text-xs md:text-sm mt-1 md:mt-2 inline-block hover:underline">Contact support</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Post Creation Modal -->
    <div id="postModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <!-- Backdrop with opacity value -->
        <div id="modalBackdrop" class="fixed inset-0 bg-black opacity-50" onclick="closePostModal()"></div>

        <!-- Modal Content -->
        <div class="relative w-full max-w-5xl mx-auto my-12 z-50">
            <div class="bg-zinc-800 p-6 md:p-8 rounded-lg shadow-xl overflow-y-auto max-h-[90vh]">
                <h3 class="text-2xl font-bold text-white mb-6">Create New Post</h3>
                <form method="POST" action="{{route('forum.store')}}" enctype="multipart/form-data">
                    @csrf
                    <input name="title" type="text" placeholder="Post Title"
                           class="w-full p-4 mb-6 text-lg bg-zinc-700 text-white border border-zinc-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>

                    <!-- Image Upload Section -->
                    <div class="mb-6">
                        <label class="block text-white mb-2">Upload Image</label>
                        <div class="flex items-center gap-4">
                            <label class="cursor-pointer bg-zinc-700 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                Choose File
                                <input type="file" id="image-upload" name="image" accept="image/*" class="hidden">
                            </label>
                            <span id="file-name" class="text-zinc-300 text-sm">No file chosen</span>
                        </div>

                        <!-- Image Preview -->
                        <div id="image-preview" class="mt-4 hidden">
                            <div class="relative inline-block">
                                <img id="preview-image" src="#" alt="Preview" class="max-h-40 rounded-lg border border-zinc-600">
                                <button type="button" id="remove-image" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Trix Editor -->
                    <input id="post-body" type="hidden" name="body" required>
                    <div class="mb-6">
                        <trix-editor input="post-body" class="trix-content bg-zinc-700 text-white border border-zinc-600 rounded-lg mt-2 min-h-[200px]"></trix-editor>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button" onclick="closePostModal()" class="px-6 py-3 text-lg bg-zinc-600 cursor-pointer hover:bg-zinc-700 text-white rounded-lg transition duration-200">Cancel</button>
                        <button type="submit" class="px-6 py-3 text-lg bg-red-600 hover:bg-red-700 text-white cursor-pointer rounded-lg transition duration-200">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <!-- Backdrop with opacity value -->
        <div class="fixed inset-0 bg-black opacity-50" onclick="closeEditModal()"></div>

        <!-- Modal Content -->
        <div class="relative w-full max-w-5xl mx-auto my-12 z-50">
            <div class="bg-zinc-800 p-6 md:p-8 rounded-lg shadow-xl overflow-y-auto max-h-[90vh]">
                <h3 class="text-2xl font-bold text-white mb-6">Edit Post</h3>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input name="title" type="text" id="edit-title" placeholder="Post Title"
                           class="w-full p-4 mb-6 text-lg bg-zinc-700 text-white border border-zinc-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>

                    <!-- Image Upload Section -->
                    <div class="mb-6">
                        <label class="block text-white mb-2">Upload Image</label>
                        <div class="flex items-center gap-4">
                            <label class="cursor-pointer bg-zinc-700 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                Choose File
                                <input type="file" id="edit-image-upload" name="image" accept="image/*" class="hidden">
                            </label>
                            <span id="edit-file-name" class="text-zinc-300 text-sm">No file chosen</span>
                        </div>

                        <!-- Current Image -->
                        <div id="current-image-container" class="mt-4">
                            <p class="text-sm text-gray-400 mb-2">Current Image:</p>
                            <div class="relative inline-block">
                                <img id="current-image" src="" alt="Current Image" class="max-h-40 rounded-lg border border-zinc-600">
                                <button type="button" id="remove-current-image" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                    ×
                                </button>
                            </div>
                        </div>

                        <!-- New Image Preview -->
                        <div id="edit-image-preview" class="mt-4 hidden">
                            <p class="text-sm text-gray-400 mb-2">New Image Preview:</p>
                            <div class="relative inline-block">
                                <img id="edit-preview-image" src="#" alt="Preview" class="max-h-40 rounded-lg border border-zinc-600">
                                <button type="button" id="edit-remove-image" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Trix Editor -->
                    <input id="edit-post-body" type="hidden" name="body" required>
                    <div class="mb-6">
                        <trix-editor input="edit-post-body" class="trix-content bg-zinc-700 text-white border border-zinc-600 rounded-lg mt-2 min-h-[200px]"></trix-editor>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button" onclick="closeEditModal()" class="px-6 py-3 text-lg bg-zinc-600 cursor-pointer hover:bg-zinc-700 text-white rounded-lg transition duration-200">Cancel</button>
                        <button type="submit" class="px-6 py-3 text-lg bg-blue-600 hover:bg-blue-700 text-white cursor-pointer rounded-lg transition duration-200">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black opacity-50" onclick="closeDeleteModal()"></div>
        <div class="relative bg-zinc-800 rounded-lg shadow-xl max-w-md w-full z-50 p-6">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Delete Post</h3>
                <p class="text-gray-400 text-center mb-6">Are you sure you want to delete this post? This action cannot be undone.</p>
                <div class="flex justify-center gap-4 w-full">
                    <button onclick="closeDeleteModal()" class="px-6 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg transition duration-200 flex-1">Cancel</button>
                    <button id="confirmDeleteBtn" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200 flex-1">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    @push('js')
        <script>
            function closeEditModal() {
                $('#editModal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
            function closePostModal() {
                $('#postModal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
            function closeDeleteModal() {
                $('#deleteModal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
                forumIdToDelete = null;
            }
            $(document).ready(function() {
                // Dropdown menu toggle
                $(document).on('click', '.dropdown-toggle', function(e) {
                    e.stopPropagation();
                    const dropdown = $(this).siblings('.dropdown-menu');
                    $('.dropdown-menu').not(dropdown).addClass('hidden');
                    dropdown.toggleClass('hidden');
                });

                // Close dropdown when clicking outside
                $(document).click(function() {
                    $('.dropdown-menu').addClass('hidden');
                });

                // Edit option click handler
                $(document).on('click', '.edit-option', function(e) {
                    e.preventDefault();
                    const forumId = $(this).data('forum-id');
                    openEditModal(forumId);
                    $(this).closest('.dropdown-menu').addClass('hidden');
                });

                // Delete option click handler
                $(document).on('click', '.delete-option', function(e) {
                    e.preventDefault();
                    const forumId = $(this).data('forum-id');
                    confirmDelete(forumId);
                    $(this).closest('.dropdown-menu').addClass('hidden');
                });

                // Modal functions
                function openPostModal() {
                    $('#postModal').removeClass('hidden');
                    $('body').addClass('overflow-hidden');
                }



                // Edit Modal functions
                function openEditModal(forumId) {
                    // Fetch forum data via AJAX
                    $.ajax({
                        url: `/forum/${forumId}/edit`,
                        method: 'GET',
                        success: function(data) {
                            // Populate the edit form
                            $('#edit-title').val(data.title);

                            // Set the form action
                            $('#editForm').attr('action', `/forum/${forumId}`);

                            // Set the current image if exists
                            const currentImageContainer = $('#current-image-container');
                            const currentImage = $('#current-image');
                            if (data.image_url) {
                                currentImage.attr('src', data.image_url);
                                currentImageContainer.removeClass('hidden');
                            } else {
                                currentImageContainer.addClass('hidden');
                            }

                            // Set the editor content
                            const editor = $('trix-editor[input="edit-post-body"]')[0].editor;
                            editor.loadHTML(data.body);

                            // Show the modal
                            $('#editModal').removeClass('hidden');
                            $('body').addClass('overflow-hidden');
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                            showNotification('Failed to load post data', 'error');
                        }
                    });
                }



                // Delete Modal functions
                let forumIdToDelete = null;

                function confirmDelete(forumId) {
                    forumIdToDelete = forumId;
                    $('#deleteModal').removeClass('hidden');
                    $('body').addClass('overflow-hidden');
                }



                // Handle delete confirmation
                $('#confirmDeleteBtn').on('click', function() {
                    if (!forumIdToDelete) return;

                    $.ajax({
                        url: `/forum/${forumIdToDelete}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data.success) {
                                // Remove the deleted forum post from the DOM
                                $(`.forum-post[data-forum-id="${forumIdToDelete}"]`).remove();
                                showNotification('Post deleted successfully', 'success');
                            } else {
                                showNotification(data.message || 'Failed to delete post', 'error');
                            }
                            closeDeleteModal();
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                            showNotification('Failed to delete post', 'error');
                            closeDeleteModal();
                        }
                    });
                });

                // Tab switching functionality
                window.switchTab = function(tabName) {
                    // Update URL with tab parameter without reloading
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabName);
                    window.history.pushState({}, '', url);

                    // Hide all tab contents
                    $('.tab-content').hide();

                    // Show selected tab content
                    $(`#${tabName}-tab`).show();

                    // Update tab button styles
                    $('.tab-button').each(function() {
                        if ($(this).data('tab') === tabName) {
                            $(this).addClass('border-b-2 border-red-600 text-white')
                                .removeClass('text-gray-400 border-transparent');
                        } else {
                            $(this).removeClass('border-b-2 border-red-600 text-white')
                                .addClass('text-gray-400 border-transparent');
                        }
                    });
                }

                // Initialize with correct tab from URL
                const urlParams = new URLSearchParams(window.location.search);
                const activeTab = urlParams.get('tab') || 'popular';
                switchTab(activeTab);

                // Image preview logic for create modal
                $('#image-upload').on('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (!file.type.match('image.*')) {
                            showNotification('Please select an image file', 'error');
                            return;
                        }

                        $('#file-name').text(file.name);
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#preview-image').attr('src', e.target.result);
                            $('#image-preview').removeClass('hidden');
                        }
                        reader.onerror = function() {
                            showNotification('Error loading image', 'error');
                        };
                        reader.readAsDataURL(file);
                    }
                });

                $('#remove-image').on('click', function() {
                    $('#image-upload').val('');
                    $('#preview-image').attr('src', '#');
                    $('#image-preview').addClass('hidden');
                    $('#file-name').text('No file chosen');
                });

                // Image preview logic for edit modal
                $('#edit-image-upload').on('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (!file.type.match('image.*')) {
                            showNotification('Please select an image file', 'error');
                            return;
                        }

                        $('#edit-file-name').text(file.name);
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#edit-preview-image').attr('src', e.target.result);
                            $('#edit-image-preview').removeClass('hidden');
                        }
                        reader.onerror = function() {
                            showNotification('Error loading image', 'error');
                        };
                        reader.readAsDataURL(file);
                    }
                });

                $('#edit-remove-image').on('click', function() {
                    $('#edit-image-upload').val('');
                    $('#edit-preview-image').attr('src', '#');
                    $('#edit-image-preview').addClass('hidden');
                    $('#edit-file-name').text('No file chosen');
                });

                $('#remove-current-image').on('click', function() {
                    // Add a hidden input to indicate the current image should be removed
                    const form = $('#editForm');
                    if ($('#remove_current_image').length === 0) {
                        form.append('<input type="hidden" name="remove_image" id="remove_current_image" value="1">');
                    }

                    // Hide the current image preview
                    $('#current-image-container').addClass('hidden');
                });

                // Close on ESC key
                $(document).on('keydown', function(e) {
                    if (e.key === "Escape") {
                        closePostModal();
                        closeEditModal();
                        closeDeleteModal();
                    }
                });

                // Handle back/forward navigation
                window.addEventListener('popstate', function() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const activeTab = urlParams.get('tab') || 'popular';
                    switchTab(activeTab);
                });

                // Upvote functionality
                $(document).on('click', '.upvote-btn', function() {
                    const button = $(this);
                    const forumId = button.data('forum-id');

                    $.ajax({
                        url: `/forums/${forumId}/upvote`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.error) {
                                showNotification(response.error, 'error');
                                return;
                            }

                            // Update UI for ALL matching forum IDs (both tabs)
                            $(`.upvote-btn[data-forum-id="${forumId}"]`).each(function() {
                                const $btn = $(this);
                                $btn.toggleClass('border-red-600 border-gray-500');
                                $btn.find('svg').toggleClass('text-red-500 text-gray-500');
                            });

                            // Update ALL upvote counts (both tabs)
                            $(`.upvote-count[data-forum-id="${forumId}"]`).each(function() {
                                const $count = $(this);
                                $count.text(response.upvotes);
                                $count.toggleClass('text-red-500 text-gray-400');
                            });
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.error || 'An error occurred';
                            showNotification(errorMessage, 'error');
                        }
                    });
                });

                // Notification function
                window.showNotification = function(message, type = 'success') {
                    const colors = {
                        success: 'bg-green-600',
                        error: 'bg-red-600',
                        info: 'bg-blue-600'
                    };

                    const notification = $(`
                    <div class="fixed top-4 right-4 px-4 py-2 ${colors[type]} text-white rounded-md shadow-md text-sm md:text-base flex items-center justify-between min-w-[200px]">
                        <span>${message}</span>
                        <button class="ml-2" onclick="$(this).parent().remove()">×</button>
                    </div>
                `);

                    $('body').append(notification);
                    setTimeout(() => notification.remove(), 3000);
                }
            });
        </script>
    @endpush

</x-app-layout>

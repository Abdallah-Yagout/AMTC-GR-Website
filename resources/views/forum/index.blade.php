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
                <button onclick="switchTab('popular')" class="tab-button text-sm md:text-base cursor-pointer pb-2 border-b-2 border-red-600 text-white font-medium" data-tab="popular">Popular</button>
                <button onclick="switchTab('newest')" class="tab-button text-sm md:text-base cursor-pointer pb-2 border-b-2 border-transparent text-gray-400 hover:text-white" data-tab="newest">Newest</button>
                <button onclick="document.getElementById('postModal').classList.remove('hidden')" class="ml-auto text-sm md:text-base cursor-pointer bg-red-600 text-white px-3 py-1 md:px-4 rounded-full mb-1 md:mb-2 hover:bg-red-700">Post</button>
            </div>

            <!-- Main Content Grid -->
            <div class="flex flex-col lg:flex-row gap-6 md:gap-8 lg:gap-10">
                <!-- Forum Posts - Responsive Width -->
                <div class="w-full lg:flex-1 space-y-3 md:space-y-4">
                    <!-- Popular Posts -->
                    <div id="popular-tab" class="tab-content">
                        @foreach ($popularForums as $forum)
                            <a href="{{route('forum.view',$forum->id)}}">
                                <div class="forum-post bg-zinc-800 p-4 md:p-6 rounded">
                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-3 md:gap-4">
                                        <!-- Forum Content -->
                                        <div class="flex-1">
                                            <h4 class="text-base md:text-lg font-semibold text-white">{{ $forum->title }}</h4>
                                            <div class="text-xs sm:text-sm text-gray-400 mt-1 md:mt-2 break-all whitespace-pre-line prose prose-invert max-w-full">
                                                {!! $forum->body !!}
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1 md:mt-2">
                                                Posted by {{ $forum->user->name }} • {{ $forum->upvotes_count }} upvotes
                                            </p>
                                        </div>

                                        <!-- Upvote Button - Responsive -->
                                        <div class="flex items-center gap-2 shrink-0">
                                            <button class="upvote-btn flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-md p-1 transition-colors duration-200 border {{ $forum->upvotedByMe ? 'border-blue-500' : 'border-primary-500' }}"
                                                    data-forum-id="{{ $forum->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4 md:h-5 md:w-5 {{ $forum->upvotedByMe ? 'text-blue-500' : 'text-primary-500' }} hover:text-primary-600"
                                                     viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                          d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                          clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <span class="text-xs upvote-count md:text-sm text-gray-400" data-forum-id="{{ $forum->id }}">{{ $forum->upvotes_count }}</span>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        @endforeach
                    </div>

                    <!-- Newest Posts -->
                    <div id="newest-tab" class="tab-content hidden">
                        @foreach ($newestForums as $forum)
                            <a href="{{route('forum.view',$forum->id)}}">
                                <div class="forum-post bg-zinc-800 p-4 md:p-6 rounded">
                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-3 md:gap-4">
                                        <div class="flex-1">
                                            <h4 class="text-base md:text-lg font-semibold text-white">{{ $forum->title }}</h4>
                                            <div class="text-xs sm:text-sm text-gray-400 mt-1 md:mt-2 break-all whitespace-pre-line">
                                                {!! $forum->body !!}
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1 md:mt-2">
                                                Posted by {{ $forum->user->name }} • {{ $forum->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <button class="upvote-btn flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-md p-1 transition-colors duration-200 border {{ $forum->upvotedByMe ? 'border-blue-500' : 'border-primary-500' }}"
                                                    data-forum-id="{{ $forum->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4 md:h-5 md:w-5 {{ $forum->upvotedByMe ? 'text-blue-500' : 'text-primary-500' }} hover:text-primary-600"
                                                     viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                          d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                          clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <span class="text-xs upvote-count md:text-sm text-gray-400" data-forum-id="{{ $forum->id }}">{{ $forum->upvotes_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        @endforeach
                    </div>
                </div>

                <!-- Sidebar - Responsive -->
                <div class="w-full lg:w-1/3 mt-6 md:mt-8 lg:mt-0 space-y-6 md:space-y-8 lg:space-y-10">
                    <div>
                        <h3 class="text-base md:text-lg font-semibold mb-3 md:mb-4">New Discussion</h3>
                        @foreach($recentDiscussions as $discussion)
                            <div class="flex items-start gap-2 text-xs md:text-sm text-gray-400 mb-3 md:mb-4">
                                <img src="{{ asset('storage'.'/'.$discussion->user->profile_photo_path) }}" alt="User Avatar" class="w-5 h-5 md:w-6 md:h-6 rounded-full object-cover">
                                <div>
                                    <p class="text-white font-medium">{{ $discussion->user->name }}</p>
                                    <p class="text-xs">{{ Str::limit($discussion->title, 50) }}</p>
                                    <p class="text-xs mt-1 md:mt-2 text-gray-400">{{ $discussion->created_at->format('d M') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div>
                        <h3 class="text-base md:text-lg font-semibold mb-2 md:mb-3">Popular Post</h3>
                        @if($popularPost)
                            <div class="text-xs md:text-sm text-gray-400">
                                <p class="text-white font-medium">{{ $popularPost->user->name }}</p>
                                <p class="text-xs">{{ Str::limit($popularPost->title, 60) }}</p>
                                <p class="text-xs mt-1 text-gray-500">{{ $popularPost->upvotes_count }} upvotes</p>
                            </div>
                        @endif
                    </div>
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

    <!-- Post Creation Modal - Responsive -->
    <div id="postModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-zinc-800 p-4 md:p-6 rounded shadow-md w-full max-w-md md:max-w-lg">
            <h3 class="text-lg font-bold text-white mb-3 md:mb-4">Create New Post</h3>
            <form method="POST" action="">
                @csrf
                <input name="title" type="text" placeholder="Post Title"
                       class="w-full p-2 mb-3 md:mb-4 text-sm md:text-base bg-zinc-700 text-white border border-zinc-600 rounded" required>
                <textarea name="body" placeholder="Write your post..."
                          class="w-full p-2 mb-3 md:mb-4 text-sm md:text-base bg-zinc-700 text-white border border-zinc-600 rounded" rows="4 md:rows-5" required></textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('postModal').classList.add('hidden')"
                            class="px-3 py-1 md:px-4 text-sm md:text-base bg-zinc-600 text-white rounded hover:bg-zinc-700">Cancel</button>
                    <button type="submit" class="px-3 py-1 md:px-4 text-sm md:text-base bg-red-600 text-white rounded hover:bg-red-700">Post</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Tab switching functionality
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected tab content
            document.getElementById(`${tabName}-tab`).classList.remove('hidden');

            // Update tab button styles
            document.querySelectorAll('.tab-button').forEach(button => {
                if (button.dataset.tab === tabName) {
                    button.classList.add('border-b-2', 'border-red-600', 'text-white');
                    button.classList.remove('text-gray-400', 'border-transparent');
                } else {
                    button.classList.remove('border-b-2', 'border-red-600', 'text-white');
                    button.classList.add('text-gray-400', 'border-transparent');
                }
            });
        }

        // Initialize with popular tab active
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('popular');
        });

        // Upvote functionality
        $(document).on('click', '.upvote-btn', function () {
            const button = $(this);
            const forumId = button.data('forum-id');

            $.ajax({
                url: `/forums/${forumId}/upvote`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.error) {
                        // Show error notification
                        const notification = $(
                            `<div class="fixed top-4 right-4 px-4 py-2 bg-red-600 text-white rounded-md shadow-md text-sm md:text-base">
                                ${response.error}
                                <button class="ml-2" onclick="$(this).parent().remove()">×</button>
                            </div>`
                        );
                        $('body').append(notification);
                        setTimeout(() => notification.remove(), 3000);
                        return;
                    }

                    // Update UI
                    $(`.upvote-count[data-forum-id="${forumId}"]`).text(response.upvotes);
                    const icon = button.find('svg');
                },
                error: function (xhr) {
                    const errorMessage = xhr.responseJSON?.error || 'An error occurred';
                    const notification = $(
                        `<div class="fixed top-4 right-4 px-4 py-2 bg-red-600 text-white rounded-md shadow-md text-sm md:text-base">
                            ${errorMessage}
                            <button class="ml-2" onclick="$(this).parent().remove()">×</button>
                        </div>`
                    );
                    $('body').append(notification);
                    setTimeout(() => notification.remove(), 3000);
                }
            });
        });
    </script>
</x-app-layout>

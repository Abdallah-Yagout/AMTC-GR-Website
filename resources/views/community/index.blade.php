<x-app-layout>
    <!-- Hero Section -->
    <section class="bg-primary-200 px-6 md:px-40 py-8">
        <h2 class="text-3xl font-bold text-white mb-2">{{__('Welcome to Our Forums')}}</h2>
        <p class="text-white">{{__('Ask your questions, get expert support, and collaborate on meaningful topics.')}}</p>
    </section>

    <div class="bg-black text-white py-10 px-4 md:px-16 min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- Tabs -->
            <div class="flex items-center gap-4 border-b border-gray-700 mb-6">
                <button onclick="switchTab('popular')" class="tab-button cursor-pointer pb-2 border-b-2 border-red-600 text-white font-medium" data-tab="popular">Popular</button>
                <button onclick="switchTab('newest')" class="tab-button cursor-pointer pb-2 text-gray-400 hover:text-white" data-tab="newest">Newest</button>
                <button onclick="document.getElementById('postModal').classList.remove('hidden')" class="ml-auto cursor-pointer bg-red-600 text-white px-4 py-1 rounded-full mb-2 hover:bg-red-700">Post</button>
            </div>

            <!-- Main Content -->
            <div class="lg:flex lg:gap-10">
                <!-- Forum Posts -->
                <div class="flex-1 space-y-4">
                    <!-- Popular Posts (default visible) -->
                    <div id="popular-tab" class="tab-content">
                        @foreach ($popularForums as $forum)
                            <div class="forum-post bg-zinc-800 p-6 rounded" data-tab="popular">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-semibold text-white">{{ $forum->title }}</h4>
                                        <p class="text-sm text-gray-400 mt-2">{{ $forum->body }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Posted by {{ $forum->user->name }} • {{ $forum->upvotes_count }} upvotes</p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button onclick="toggleUpvote({{ $forum->id }}, this)"
                                                class="upvote-btn flex items-center justify-center w-8 h-8 border border-primary-500 rounded-md p-1 hover:bg-primary-500/10 transition-colors duration-200"
                                                data-forum-id="{{ $forum->id }}">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-primary-500 hover:text-primary-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                        <span id="upvotes-{{ $forum->id }}">{{ $forum->upvotes_count }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Newest Posts (hidden by default) -->
                    <div id="newest-tab" class="tab-content hidden">
                        @foreach ($newestForums as $forum)
                            <div class="forum-post bg-zinc-800 p-6 rounded" data-tab="newest">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-semibold text-white">{{ $forum->title }}</h4>
                                        <p class="text-sm text-gray-400 mt-2">{{ $forum->body }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Posted by {{ $forum->user->name }} • {{ $forum->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="toggleUpvote({{ $forum->id }}, this)"
                                            class="upvote-btn flex items-center justify-center w-8 h-8 border border-primary-500 rounded-md p-1 hover:bg-primary-500/10 transition-colors duration-200"
                                            data-forum-id="{{ $forum->id }}"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-primary-500 hover:text-primary-600"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                        <span id="upvotes-{{ $forum->id }}" class="text-sm font-medium text-gray-400">{{ $forum->upvotes_count }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>

                <!-- Sidebar -->
                <div class="w-full lg:w-1/3 mt-10 lg:mt-0 space-y-10">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">New Discussion</h3>

                        @foreach($recentDiscussions as $discussion)
                            <div class="flex items-start gap-2 text-sm text-gray-400 mb-4">
                                <img src="{{ asset('img/image11.png') }}" alt="User Avatar" class="w-6 h-6 rounded-full object-cover">
                                <div>
                                    <p class="text-white font-medium">{{ $discussion->user->name }}</p>
                                    <p class="text-xs text-white">{{ Str::limit($discussion->title, 50) }}</p>
                                    <p class="text-sm mt-2 text-gray-400">{{ $discussion->created_at->format('d M') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>



                    <div>
                        <h3 class="text-lg font-semibold mb-2">Popular Post</h3>
                        @if($popularPost)
                            <div class="text-sm text-gray-400">
                                <p class="text-white font-medium">{{ $popularPost->user->name }}</p>
                                <p class="text-xs">{{ Str::limit($popularPost->title, 60) }}</p>
                                <p class="text-xs mt-1 text-gray-500">{{ $popularPost->upvotes_count }} upvotes</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
                <div class="bg-zinc-800 p-6 rounded">
                    <h4 class="text-lg font-semibold mb-2">News & Updates</h4>
                    <p class="text-sm text-gray-400">Check out the latest news and updates from the community.</p>
                    <a href="#" class="text-red-500 text-sm mt-2 inline-block hover:underline">View all updates</a>
                </div>
                <div class="bg-zinc-800 p-6 rounded">
                    <h4 class="text-lg font-semibold mb-2">Support</h4>
                    <p class="text-sm text-gray-400">Feel free to contact us, we're here to help you.</p>
                    <a href="#" class="text-red-500 text-sm mt-2 inline-block hover:underline">Contact support</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Post Creation Modal -->
    <div id="postModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-zinc-800 p-6 rounded shadow-md w-full max-w-lg">
            <h3 class="text-lg font-bold text-white mb-4">Create New Post</h3>
            <form method="POST" action="">
                @csrf
                <input name="title" type="text" placeholder="Post Title" class="w-full p-2 mb-4 bg-zinc-700 text-white border border-zinc-600 rounded" required>
                <textarea name="body" placeholder="Write your post..." class="w-full p-2 mb-4 bg-zinc-700 text-white border border-zinc-600 rounded" rows="5" required></textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('postModal').classList.add('hidden')" class="px-4 py-1 bg-zinc-600 text-white rounded hover:bg-zinc-700">Cancel</button>
                    <button type="submit" class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700">Post</button>
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
                    button.classList.remove('text-gray-400');
                } else {
                    button.classList.remove('border-b-2', 'border-red-600', 'text-white');
                    button.classList.add('text-gray-400');
                }
            });
        }

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
            $(`#upvotes-${forumId}`).text(response.upvotes);
            const icon = button.find('svg');

            if (response.action === 'upvoted') {
            icon.removeClass('text-primary-500').addClass('text-red-600');
        } else {
            icon.removeClass('text-red-600').addClass('text-primary-500');
        }
        },
            error: function () {
            alert('You must be logged in to upvote.');
        }
        });
        });

    </script>
</x-app-layout>

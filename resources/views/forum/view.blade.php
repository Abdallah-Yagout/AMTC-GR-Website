<x-app-layout>
    <div class="max-w-8xl mx-auto px-6 sm:px-6 lg:px-16 py-6">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content (70% width) -->
            <div class="w-full lg:w-10/12">
                <section class="bg-gray-800 rounded-lg p-6 text-white">
                    <!-- Upvote Section -->
                    <div class="flex items-start gap-4">
                        <div class="flex flex-col items-center">
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
                            <span class="text-xs upvote-count md:text-sm text-gray-400 mt-1" data-forum-id="{{ $forum->id }}">{{ $forum->upvotes_count }}</span>
                        </div>

                        <div class="flex-1">
                            <!-- Title -->
                            <h1 class="text-xl md:text-2xl font-bold mb-4">{{ $forum->title }}</h1>

                            <!-- Author Info -->
                            <div class="flex items-center gap-3 mb-6">
                                <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/'.$forum->user->profile_photo_path) }}" alt="{{ $forum->user->name }}">
                                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-300">
                                    <span>{{ $forum->user->name }}</span>
                                    <span class="text-gray-500">•</span>
                                    <span>{{ \Illuminate\Support\Carbon::parse($forum->created_at)->format('j F Y') }}</span>
                                    <span class="text-gray-500">•</span>
                                    <span>{{ $forum->upvotes }} {{ __('upvotes') }}</span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="prose prose-invert max-w-none">
                                {!! $forum->body !!}
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Comments Section (You can add this later) -->
                <section class="mt-8 bg-gray-800 rounded-lg p-6 text-white">
                    <h2 class="text-lg font-bold mb-4">Comments</h2>
                    <!-- Comments will go here -->
                </section>
            </div>

            <!-- Sidebar (30% width) -->
            <div class="w-full lg:w-2/12 space-y-6">
                <!-- New Discussions -->
                <section class="bg-gray-800 rounded-lg p-6 text-white">
                    <h2 class="text-lg font-bold mb-4 border-b border-gray-700 pb-2">New Discussions</h2>
                    <div class="space-y-4">
                        @foreach($newDiscussions as $discussion)
                            <a href="{{ route('forum.view', $discussion) }}" class="block hover:bg-gray-700 p-2 rounded transition">
                                <h3 class="font-medium">{{ $discussion->title }}</h3>
                                <div class="flex items-center gap-2 text-xs text-gray-400 mt-1">
                                    <span>{{ $discussion->user->name }}</span>
                                    <span>•</span>
                                    <span>{{ $discussion->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>

                <!-- Popular Posts -->
                <section class="bg-gray-800 rounded-lg p-6 text-white">
                    <h2 class="text-lg font-bold mb-4 border-b border-gray-700 pb-2">Popular Posts</h2>
                    <div class="space-y-4">
                        @foreach($popularPosts as $post)
                            <a href="{{ route('forum.view', $post) }}" class="block hover:bg-gray-700 p-2 rounded transition">
                                <h3 class="font-medium">{{ $post->title }}</h3>
                                <div class="flex items-center gap-2 text-xs text-gray-400 mt-1">
                                    <span>{{ $post->upvotes }} upvotes</span>
                                    <span>•</span>
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>

@props(['forum'])

<div class="forum-post mb-3 bg-zinc-800 p-4 md:p-6 rounded" data-forum-id="{{ $forum->id }}">
    <div class="flex flex-col sm:flex-row justify-between items-start gap-3 md:gap-4">
        <!-- Forum Content with Image -->
        <a href="{{route('forum.show',$forum->slug)}}" class="flex items-start gap-3 w-full sm:w-auto">
            <!-- Image Container -->
            <div class="flex-shrink-0 w-12 h-12 md:w-16 md:h-16 rounded-md overflow-hidden bg-zinc-700 border border-zinc-600">
                @if($forum->image)
                    <img src="{{ asset('storage'.'/'.$forum->image) }}"
                         alt="{{ $forum->title }}"
                         class="w-full h-full object-cover">
                @else
                    <!-- Default image with gradient and icon -->
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-600 to-blue-500 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Text Content -->
            <div class="flex-1 min-w-0">
                <h4 class="text-base md:text-lg font-semibold text-white">{{ $forum->title }}</h4>
                <div class="flex flex-wrap items-center gap-x-3 text-xs text-gray-500 mt-1 md:mt-2">
                    <span>{{ __('Posted by') }} {{ $forum->user->name }}</span>
                    <span>•</span>
                    <span>{{ $forum->upvotes_count > 0 ? $forum->upvotes_count : 0 }} {{ __('upvotes') }}</span>
                    <span>•</span>
                    <span>{{ $forum->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </a>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 shrink-0">
            @if(auth()->id() === $forum->user_id)
                <div class="relative">
                    <button class="dropdown-toggle cursor-pointer flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-md p-1 transition-colors duration-200 hover:bg-gray-700/50">
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

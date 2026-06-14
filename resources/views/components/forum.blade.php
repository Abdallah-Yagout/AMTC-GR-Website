@props(['forum'])

<div class="forum-post forum-post-card mb-3 md:mb-4" data-forum-id="{{ $forum->id }}">
    <div class="flex flex-col sm:flex-row justify-between items-start gap-3 md:gap-4">
        <a href="{{ route('forum.show', $forum->slug) }}" class="forum-post-link flex items-start gap-3 w-full sm:w-auto min-w-0">
            <div class="forum-post-thumb flex-shrink-0 w-12 h-12 md:w-16 md:h-16 rounded-lg overflow-hidden">
                @if($forum->image)
                    <img src="{{ asset('storage/'.$forum->image) }}"
                         alt="{{ $forum->title }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="forum-post-thumb-placeholder w-full h-full flex items-center justify-center" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 md:h-8 md:w-8 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <h4 class="forum-post-title text-base md:text-lg font-semibold text-white">{{ $forum->title }}</h4>
                <div class="forum-post-meta flex flex-wrap items-center gap-x-3 gap-y-0.5 text-xs text-zinc-500 mt-1 md:mt-2">
                    <span>{{ __('Posted by') }} {{ $forum->user->name }}</span>
                    <span class="text-zinc-600" aria-hidden="true">•</span>
                    <span>{{ $forum->upvotes_count > 0 ? $forum->upvotes_count : 0 }} {{ __('upvotes') }}</span>
                    <span class="text-zinc-600" aria-hidden="true">•</span>
                    <span>{{ $forum->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </a>

        <div class="forum-post-actions flex items-center gap-2 shrink-0">
            @if(auth()->id() === $forum->user_id)
                <div class="relative">
                    <button type="button" class="forum-post-menu-btn dropdown-toggle cursor-pointer flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-md p-1" aria-expanded="false" aria-haspopup="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                    </button>
                    <div class="forum-dropdown dropdown-menu hidden absolute end-0 mt-2 w-48 rounded-lg z-50 py-1">
                        <a href="#" class="forum-dropdown-item edit-option flex items-center gap-2 px-4 py-2 text-sm" data-forum-id="{{ $forum->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </a>
                        <a href="#" class="forum-dropdown-item delete-option flex items-center gap-2 px-4 py-2 text-sm" data-forum-id="{{ $forum->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('Delete') }}
                        </a>
                    </div>
                </div>
            @endif
            <button type="button" class="forum-upvote-btn upvote-btn flex items-center justify-center w-7 h-7 md:w-8 md:h-8 rounded-md p-1 border {{ $forum->upvotedByMe ? 'is-active' : '' }}"
                    data-forum-id="{{ $forum->id }}" aria-label="{{ __('Upvote') }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4 md:h-5 md:w-5 forum-upvote-icon"
                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                          clip-rule="evenodd" />
                </svg>
            </button>
            <span class="forum-upvote-count text-xs upvote-count md:text-sm {{ $forum->upvotedByMe ? 'is-active' : '' }}" data-forum-id="{{ $forum->id }}">{{ $forum->upvotes_count }}</span>
        </div>
    </div>
</div>

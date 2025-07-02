<div class="comment pl-0 border-b border-gray-700 pb-4 sm:pb-6 last:border-0 last:pb-0" id="comment-{{ $comment->id }}">
    <div class="flex gap-2 sm:gap-3">
        <div class="flex-shrink-0">
            <img class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover"
                 src="{{ $comment->user->avatar_url }}"
                 alt="{{ $comment->user->name }}">
        </div>
        <div class="flex-1 min-w-0"> <!-- Added min-w-0 to prevent overflow -->
            <div class="flex flex-wrap items-center gap-1 sm:gap-2 text-xs sm:text-sm mb-1">
                <span class="font-medium text-primary truncate">{{ $comment->user->name }}</span>
                <span class="text-gray-500 hidden sm:inline">•</span>
                <span class="text-gray-400 whitespace-nowrap">{{ $comment->created_at->diffForHumans() }}</span>

                <div class="flex gap-1 sm:gap-2 ml-auto sm:ml-2">
                    @auth
                        <button class="cursor-pointer text-blue-400 hover:text-blue-500 hover:underline reply-btn text-xs sm:text-sm"
                                data-comment-id="{{ $comment->id }}">
                            {{__('Reply')}}
                        </button>
                    @endauth

                    @can('delete', $comment)
                        <form class="delete-comment-form inline"
                              action="{{ route('comments.destroy', $comment) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-400 hover:underline cursor-pointer hover:text-red-400 text-xs sm:text-sm">
                                {{__('Delete')}}
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            <div class="prose prose-invert max-w-none text-xs sm:text-sm mb-2">
                {!! nl2br(e($comment->body)) !!}
            </div>

            @auth
                <div class="mt-2 sm:mt-3 hidden" id="reply-form-{{ $comment->id }}">
                    <form class="reply-form"
                          action="{{ route('comments.store', $forum) }}"
                          method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="body" rows="2"
                                  class="w-full bg-gray-700 border border-gray-600 rounded-md p-2 text-white text-xs sm:text-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="{{__('Write your reply...')}}" required></textarea>
                        <div class="mt-2 flex justify-end gap-2">
                            <button type="button"
                                    class="cancel-reply cursor-pointer px-2 py-1 sm:px-3 sm:py-1 bg-gray-600 hover:bg-gray-500 rounded-md text-white text-xs sm:text-sm"
                                    data-comment-id="{{ $comment->id }}">
                                {{__('Cancel')}}
                            </button>
                            <button type="submit"
                                    class="px-2 py-1 sm:px-3 sm:py-1 hover:underline cursor-pointer bg-blue-600 hover:bg-blue-500 rounded-md text-white text-xs sm:text-sm">
                                {{__('Reply')}}
                            </button>
                        </div>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    @if($comment->replies->count() > 0)
        <button class="toggle-replies-btn hover:underline cursor-pointer text-xs text-blue-400 hover:text-blue-500 mt-2"
                data-comment-id="{{ $comment->id }}">
            {{ __('Show Replies') }} ({{ $comment->replies->count() }})
        </button>

        <div class="replies-container mt-3 sm:mt-4 ml-5 sm:ml-10 space-y-3 sm:space-y-4 pl-3 sm:pl-4 border-l-2 border-gray-700 hidden"
             id="replies-{{ $comment->id }}">
            @foreach($comment->replies as $reply)
                @include('forum.reply', ['reply' => $reply])
            @endforeach
        </div>
    @endif


</div>

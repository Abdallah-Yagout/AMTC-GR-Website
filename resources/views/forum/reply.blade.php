<div class="reply pt-4 ps-2" id="reply-{{ $reply->id }}">
    <div class="flex gap-3">
        <div class="flex-shrink-0">
            <img class="w-7 h-7 rounded-full object-cover"
                 src="{{ $reply->user->avatar_url }}"
                 alt="{{ $reply->user->name }}">
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2 text-xs mb-1">
                <span class="font-medium">{{ $reply->user->name }}</span>
                <span class="text-gray-500">•</span>
                <span class="text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>

                @can('delete', $reply)
                    <form class="delete-comment-form inline"
                          action="{{ route('comments.destroy', $reply) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-red-400 hover:underline cursor-pointer hover:text-red-400 text-xs sm:text-sm">
                            Delete
                        </button>
                    </form>
                @endcan
            </div>

            <div class="prose prose-invert max-w-none text-xs">
                {!! nl2br(e($reply->body)) !!}
            </div>
        </div>
    </div>
</div>

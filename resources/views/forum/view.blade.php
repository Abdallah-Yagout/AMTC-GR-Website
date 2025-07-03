<x-app-layout>
    <div class="max-w-8xl mx-auto px-6 sm:px-6 lg:px-16 py-6">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content (70% width) -->
            <div class="w-full lg:w-9/12">
                <section class="rounded-lg py-6 text-white">
                    <!-- Upvote Section -->
                    <div class="flex items-start gap-4">
                        <div class="flex flex-col items-center">
                            <button
                                class="upvote-btn cursor-pointer flex flex-col items-center justify-center w-12 h-12 md:w-12 md:h-12 rounded-md p-1 transition-colors duration-200 border {{ $forum->upvotedByMe ? 'border-red-600' : 'border-primary-500' }}"
                                data-forum-id="{{ $forum->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4 md:h-5 md:w-5 {{ $forum->upvotedByMe ? 'text-red-500' : 'text-primary-500' }} hover:text-primary-600"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs upvote-count md:text-sm"
                                      data-forum-id="{{ $forum->id }}">{{ $forum->upvotes_count }}</span>
                            </button>
                        </div>

                        <div class="flex-1">
                            <!-- Title -->
                            <h1 class="text-xl md:text-2xl font-bold mb-4">{{ $forum->title }}</h1>

                            <!-- Author Info -->
                            <div class="flex items-center gap-3 mb-6">
                                <img class="w-8 h-8 rounded-full object-cover"
                                     src="{{ $forum->user->avatar_url }}"
                                     alt="{{ $forum->user->name }}">
                                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-300">
                                    <span>{{ $forum->user->name }}</span>
                                    <span class="text-gray-500">•</span>
                                    <span>{{ \Illuminate\Support\Carbon::parse($forum->created_at)->format('j F Y') }}</span>
                                    <span class="text-gray-500">•</span>
                                    <span><span id="comments-count">{{ $forum->comments_count }}</span> {{ __('Comments') }}</span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="prose prose-invert max-w-none">
                                {!! $forum->body !!}
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Comments Section Container -->
                <section class="rounded-lg p-4 sm:p-6 text-white">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                        <h2 class="text-lg sm:text-xl font-bold">
                            {{ __('Comments') }} (<span id="comments-count">{{ $forum->comments_count }}</span>)
                        </h2>
                        <button id="toggle-comments"
                                class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-md text-sm text-white self-end sm:self-auto">
                            {{__('Hide Comments')}}
                        </button>
                    </div>

                    <!-- Comments Content (toggleable) -->
                    <div id="comments-content">
                        @auth
                            <div class="mb-6" id="comment-form-container">
                                <form id="comment-form" action="{{ route('comments.store', $forum) }}" method="POST">
                                    @csrf
                                    <textarea name="body" rows="3"
                                              class="w-full bg-[#171717] border border-gray-600 rounded-md p-3 text-white focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                              placeholder="{{__('Add your comment...')}}" required></textarea>
                                    <div class="mt-2 flex justify-end">
                                        <button type="submit"
                                                class="px-3 py-1 sm:px-4 sm:py-2 cursor-pointer bg-red-600 hover:bg-red-700 rounded-md text-white text-sm sm:text-base font-medium">
                                            {{ __('Comment') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="mb-6 p-3 sm:p-4 bg-gray-700 rounded-md">
                                <p class="text-gray-300 text-sm sm:text-base">
                                    {{ __('Please') }} <a href="{{ route('login') }}"
                                                          class="text-blue-400 hover:underline">{{ __('login') }}</a> {{ __('to post a comment.') }}
                                </p>
                            </div>
                        @endauth

                        <!-- Comments List -->
                        <div id="comments-list" class="space-y-4 sm:space-y-6">
                            @forelse($forum->comments->sortByDesc('created_at')->take(10) as $comment)
                                @include('forum.comment', [
'comment' => $comment,
'forum' => $forum // Add this line
])
                            @empty
                                <div class="text-center py-4 text-gray-400 text-sm sm:text-base no-comments">
                                    No comments yet. Be the first to comment!
                                </div>
                            @endforelse
                        </div>
                        @if($forum->comments->count() > 10)
                            <div class="mt-4 text-center">
                                <button id="load-more-comments"
                                        data-page="2"
                                        data-total="{{ $forum->comments->count() }}"
                                        class="px-4 py-2 bg-blue-400 cursor-pointer hover:bg-blue-600 rounded-md text-white text-sm sm:text-base">
                                    {{__('Show More')}} ({{ $forum->comments->count() - 10 }})
                                </button>
                            </div>
                        @endif
                    </div>
                </section>
            </div>


            <!-- Sidebar (30% width) -->
            <div class="w-full border-l border-[#454545] lg:w-3/12 space-y-6">
                <!-- New Discussions -->
                <section class="p-6 text-white">
                    <h2 class="text-lg font-bold mb-4 border-b border-gray-700 pb-2">{{__('New Discussions')}}</h2>
                    <div class="space-y-4">
                        @foreach($newDiscussions as $discussion)
                            <div class="flex flex-col p-3 rounded transition">
                                <div class="flex items-center gap-3 mb-2">
                                    <img class="w-8 h-8 rounded-full object-cover"
                                         src="{{ $discussion->user->avatar_url }}"
                                         alt="{{ $discussion->user->name }}">
                                    <span class="font-medium text-sm">
                                        {{ explode(' ', $discussion->user->name)[0] }}
                                    </span>
                                </div>
                                <a href="{{ route('forum.show', $discussion->slug) }}"
                                   class="text-white hover:underline mb-1">
                                    <h3 class="font-medium text-base line-clamp-2">{{ $discussion->title }}</h3>
                                </a>
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    <span>{{ \Illuminate\Support\Carbon::parse($discussion->created_at)->format('j F') }}</span>
                                    <span>•</span>
                                    <span>{{ $discussion->comments_count }} {{__('Comments')}}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Popular Posts -->
                <section class="rounded-lg p-6 text-white">
                    <h2 class="text-lg font-bold mb-4 border-b border-gray-700 pb-2">{{__('Popular Posts')}}</h2>
                    <div class="space-y-4">
                        @foreach($popularPosts as $post)
                            <div class="flex flex-col p-3 rounded transition">
                                <div class="flex items-center gap-3 mb-2">
                                    <img class="w-8 h-8 rounded-full object-cover"
                                         src="{{ $post->user->avatar_url }}"
                                         alt="{{ $post->user->name }}">
                                    <span class="font-medium text-sm">
                                        {{ explode(' ', $post->user->name)[0] }}
                                    </span>
                                </div>
                                <a href="{{ route('forum.show', $post->slug) }}"
                                   class="text-white hover:underline mb-1">
                                    <h3 class="font-medium text-base line-clamp-2">{{ $post->title }}</h3>
                                </a>
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    <span>{{ \Illuminate\Support\Carbon::parse($post->created_at)->format('j F') }}</span>
                                    <span>•</span>
                                    <span>{{ $post->upvotes_count }} {{__('upvotes')}}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function () {
                const commentsPerLoad = 10;

                $('#load-more-comments').on('click', function () {
                    const button = $(this);
                    const page = button.data('page');
                    const forumId = {{ $forum->id }};
                    const totalComments = button.data('total');

                    button.prop('disabled', true).html(`
    <span class="inline-flex items-center">
        {{ __('Loading...') }}
                    <svg class="animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
`);


                    $.ajax({
                        url: '/forum/' + forumId + '/comments',
                        method: 'GET',
                        data: {
                            page: page,
                            per_page: commentsPerLoad
                        },
                        success: function (response) {
                            if (response.html) {
                                $('#comments-list').append(response.html);

                                // Update button for next load
                                const loadedCount = (page * commentsPerLoad);
                                const remaining = totalComments - loadedCount;

                                if (remaining > 0) {
                                    button.data('page', page + 1);
                                    button.html(`Show More Comments (${remaining})`);
                                } else {
                                    button.parent().remove(); // Remove the button container
                                }
                            }
                        },
                        error: function (xhr) {
                            console.error('Error loading comments');
                            button.html('Error - Try Again').prop('disabled', false);
                        },
                        complete: function () {
                            button.prop('disabled', false);
                        }
                    });
                });
            });

            $(document).ready(function () {
                // Handle new comment submission
                $('#comment-form').on('submit', function (e) {
                    e.preventDefault();
                    const form = $(this);
                    submitCommentForm(form);
                });

                // Handle reply submission
                $(document).on('submit', '.reply-form', function (e) {
                    e.preventDefault();
                    const form = $(this);
                    submitCommentForm(form, true);
                });

                function submitCommentForm(form, isReply = false) {
                    const button = form.find('button[type="submit"]');
                    const textarea = form.find('textarea');

                    if (textarea.val().trim() === '') {
                        textarea.focus();
                        return;
                    }

                    button.prop('disabled', true);

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                // Remove "no comments" message if it exists
                                $('.no-comments').remove();

                                if (isReply) {
                                    const commentId = form.find('input[name="parent_id"]').val();
                                    const commentElement = $(`#comment-${commentId}`);
                                    let repliesContainer = commentElement.find('.replies-container');

                                    // Create replies container if it doesn't exist
                                    if (!repliesContainer.length) {
                                        repliesContainer = $(`<div class="replies-container mt-4 ml-10 space-y-4 pl-4 border-l-2 border-gray-700"></div>`);
                                        commentElement.append(repliesContainer);
                                    }

                                    // Append new reply
                                    repliesContainer.append(response.html);

                                    // Hide form and clear textarea
                                    form.closest('[id^="reply-form-"]').addClass('hidden');
                                } else {
                                    // Prepend new comment
                                    $('#comments-list').prepend(response.html);
                                }

                                // Update comments count
                                $('#comments-count').text(response.comment_count);

                                // Clear textarea
                                textarea.val('');
                            }
                        },
                        error: function (xhr) {
                            const errorMessage = xhr.responseJSON?.message || 'An error occurred';
                            console.log(errorMessage);
                        },
                        complete: function () {
                            button.prop('disabled', false);
                        }
                    });
                }

                // Handle comment deletion
                $(document).ready(function () {
                    let currentForm = null;

                    // Handle delete form submission
                    $(document).on('submit', '.delete-comment-form', function (e) {
                        e.preventDefault();
                        currentForm = $(this);

                        // Show modal with animation
                        const modal = $('#delete-modal');
                        const content = $('#modal-content');

                        modal.removeClass('hidden');
                        setTimeout(() => {
                            content.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
                        }, 10);
                    });

                    // Confirm deletion
                    $('#confirm-btn').on('click', function () {
                        if (!currentForm) return;

                        // Add loading state
                        const btn = $(this);
                        btn.prop('disabled', true).html(`
      <span class="inline-flex items-center">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Deleting...
      </span>
    `);

                        $.ajax({
                            url: currentForm.attr('action'),
                            method: 'POST',
                            data: currentForm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-HTTP-Method-Override': 'DELETE'
                            },
                            success: function (response) {
                                if (response.success) {
                                    // Remove comment and update count
                                    currentForm.closest('.comment, .reply').fadeOut(300, function () {
                                        $(this).remove();
                                        const currentCount = parseInt($('#comments-count').text());
                                        $('#comments-count').text(currentCount - 1);
                                    });
                                }
                            },
                            error: function (xhr) {
                                showToast('Failed to delete comment', 'error');
                            },
                            complete: function () {
                                closeModal();
                                btn.prop('disabled', false).text('Delete');
                            }
                        });
                    });

                    // Cancel deletion
                    $('#cancel-btn').on('click', closeModal);

                    // Close modal when clicking outside
                    $('#delete-modal').on('click', function (e) {
                        if (e.target === this) closeModal();
                    });

                    // Close modal with animation
                    function closeModal() {
                        const modal = $('#delete-modal');
                        const content = $('#modal-content');

                        content.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                        setTimeout(() => {
                            modal.addClass('hidden');
                            currentForm = null;
                        }, 300);
                    }

                    // Show toast notification
                    function showToast(message, type = 'error') {
                        const toast = $(`
      <div class="fixed top-4 right-4 z-50">
        <div class="bg-${type === 'error' ? 'red' : 'green'}-600 text-white px-4 py-2 rounded-md shadow-lg flex items-center">
          <span>${message}</span>
          <button class="ml-2 text-white hover:text-gray-200">
            &times;
          </button>
        </div>
      </div>
    `);

                        $('body').append(toast);
                        toast.find('button').on('click', function () {
                            toast.fadeOut(200, function () {
                                $(this).remove();
                            });
                        });

                        setTimeout(() => {
                            toast.fadeOut(200, function () {
                                $(this).remove();
                            });
                        }, 3000);
                    }
                });
                // Handle reply button clicks
                $(document).on('click', '.reply-btn', function () {
                    const commentId = $(this).data('comment-id');
                    const replyForm = $('#reply-form-' + commentId);

                    // Hide all other reply forms
                    $('[id^="reply-form-"]').not(replyForm).addClass('hidden');

                    // Toggle current reply form
                    replyForm.toggleClass('hidden');

                    // Scroll to form if it's being shown
                    if (!replyForm.hasClass('hidden')) {
                        replyForm[0].scrollIntoView({behavior: 'smooth', block: 'nearest'});
                    }
                });

                // Handle cancel buttons
                $(document).on('click', '.cancel-reply', function () {
                    const commentId = $(this).data('comment-id');
                    $('#reply-form-' + commentId).addClass('hidden');
                });

                // Comments toggle functionality
                const toggleCommentsBtn = $('#toggle-comments');
                const commentsContent = $('#comments-content');

                // Initialize from localStorage
                if (localStorage.getItem('commentsContentVisible') === 'false') {
                    commentsContent.hide();
                    toggleCommentsBtn.text('Show Comments');
                }

                // Toggle click handler
                toggleCommentsBtn.on('click', function () {
                    commentsContent.toggle();
                    const isVisible = commentsContent.is(':visible');
                    $(this).text(isVisible ? 'Hide Comments' : 'Show Comments');
                    localStorage.setItem('commentsContentVisible', isVisible);
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

                            // Update ALL upvote buttons and counts for this forum
                            $(`.upvote-btn[data-forum-id="${forumId}"]`).each(function () {
                                const $btn = $(this);
                                $btn.toggleClass('border-red-600 border-primary-500');
                                $btn.find('svg').toggleClass('text-red-500 text-primary-500');
                            });

                            $(`.upvote-count[data-forum-id="${forumId}"]`).each(function () {
                                const $count = $(this);
                                $count.text(response.upvotes);
                                $count.toggleClass('text-red-500 text-primary-500');
                            });
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
            });
            $(document).on('click', '.toggle-replies-btn', function () {
                const commentId = $(this).data('comment-id');
                const repliesContainer = $('#replies-' + commentId);

                repliesContainer.toggleClass('hidden');

                const isVisible = !repliesContainer.hasClass('hidden');
                $(this).text(isVisible ? 'Hide Replies' : 'Show Replies (' + repliesContainer.children().length + ')');
            });

        </script>
    @endpush
</x-app-layout>

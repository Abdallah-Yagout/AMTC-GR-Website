<x-app-layout>
    <div class="forum-community-page">
        <header class="forum-community-hero">
            <div class="forum-community-hero-accent" aria-hidden="true"></div>
            <div class="forum-community-hero-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 md:py-11">
                <p class="forum-community-kicker">{{ __('Toyota Gazoo Racing') }}</p>
                <h1 class="forum-community-title">{{ __('Welcome to Our Forums') }}</h1>
                <p class="forum-community-lead">{{ __('Ask your questions, get expert support, and collaborate on meaningful topics.') }}</p>
            </div>
        </header>

        <div class="forum-community-body text-white py-8 md:py-12 px-4 md:px-6 lg:px-8 min-h-screen">
            <div class="max-w-7xl mx-auto">
                <div class="forum-community-toolbar flex flex-wrap items-center gap-3 md:gap-4 mb-8">
                    <div class="forum-community-tabs flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            onclick="switchTab('newest')"
                            class="forum-community-tab tab-button {{ $activeTab === 'newest' ? 'is-active' : '' }}"
                            data-tab="newest"
                        >{{ __('Newest') }}</button>
                        <button
                            type="button"
                            onclick="switchTab('popular')"
                            class="forum-community-tab tab-button {{ $activeTab === 'popular' ? 'is-active' : '' }}"
                            data-tab="popular"
                        >{{ __('Popular') }}</button>
                    </div>
                    <button type="button" onclick="openPostModal()" class="forum-community-post-btn ms-auto">
                        {{ __('Post') }}
                    </button>
                </div>

            <!-- Main Content Grid -->
            <div class="flex flex-col lg:flex-row gap-6 md:gap-8 lg:gap-10">
                <!-- Forum Posts - Responsive Width -->
                <div class="w-full lg:flex-1 space-y-3 md:space-y-4">
                    <!-- Popular Posts -->
                    <div id="popular-tab" class="tab-content" style="{{ $activeTab !== 'popular' ? 'display: none;' : '' }}">
                        @foreach ($popularForums as $forum)
                            <x-forum :forum="$forum" />
                        @endforeach
                        <div class="forum-community-pagination mt-8">
                            {{ $popularForums->appends(['tab' => 'popular'])->links() }}
                        </div>
                    </div>

                    <!-- Newest Posts -->
                    <div id="newest-tab" class="tab-content" style="{{ $activeTab !== 'newest' ? 'display: none;' : '' }}">
                        @foreach ($newestForums as $forum)
                            <x-forum :forum="$forum" />

                        @endforeach
                        <div class="forum-community-pagination mt-8">
                            {{ $newestForums->appends(['tab' => 'newest'])->links() }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Responsive -->
                @if($recentDiscussions->isNotEmpty()&&$popularForums->isNotEmpty())
                    <aside class="forum-community-sidebar w-full lg:w-3/12 space-y-6 lg:ps-8 lg:border-s border-white/10">
                        <!-- New Discussions -->
                        @if($recentDiscussions->isNotEmpty())
                            <section class="forum-community-side-panel p-5 md:p-6 text-white">
                                <h2 class="forum-community-side-heading">{{ __('New Discussions') }}</h2>
                                <div class="space-y-3">
                                    @foreach($recentDiscussions as $discussion)
                                        <div class="forum-community-side-item flex flex-col p-3 rounded-lg transition-colors">
                                            <!-- First line: Image and first name -->
                                            <div class="flex items-center gap-3 mb-2">
                                                <img class="w-8 h-8 rounded-full object-cover"
                                                     src="{{ $discussion->user->profile_photo_url }}"
                                                     alt="{{ $discussion->user->name }}">
                                                <span class="font-medium text-sm">
                                            {{ explode(' ', $discussion->user->name)[0] }}
                                        </span>
                                            </div>

                                            <!-- Second line: Discussion title -->
                                            <a href="{{ route('forum.show', $discussion->slug) }}" class="forum-community-side-link mb-1">
                                                <h3 class="font-medium text-base line-clamp-2">{{ $discussion->title }}</h3>
                                            </a>

                                            <!-- Third line: Date and comments count -->
                                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                                <span>{{\Illuminate\Support\Carbon::parse($discussion->created_at)->format('j F')}}</span>
                                                <span>•</span>
                                                @if(!$discussion->comments_count >0)
                                                    <span>0 {{__('Comments')}}</span>

                                                @else
                                                    <span>{{ $discussion->comments_count }} {{__('comments')}}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif


                        <!-- Popular Posts -->
                        @if($popularForums->isNotEmpty())
                            <section class="forum-community-side-panel p-5 md:p-6 text-white">
                                <h2 class="forum-community-side-heading">{{ __('Popular Posts') }}</h2>
                                <div class="space-y-3">
                                    @foreach($popularForums as $post)
                                        <div class="forum-community-side-item flex flex-col p-3 rounded-lg transition-colors">
                                            <!-- First line: Image and first name -->
                                            <div class="flex items-center gap-3 mb-2">
                                                <img class="w-8 h-8 rounded-full object-cover"
                                                     src="{{ $post->user->profile_photo_url }}"
                                                     alt="{{ $post->user->name }}">
                                                <span class="font-medium text-sm">
                                            {{ explode(' ', $post->user->name)[0] }}
                                        </span>
                                            </div>

                                            <!-- Second line: Discussion title -->
                                            <a href="{{ route('forum.show', $post->slug) }}" class="forum-community-side-link mb-1">
                                                <h3 class="font-medium text-base line-clamp-2">{{ $post->title }}</h3>
                                            </a>

                                            <!-- Third line: Date and comments count -->
                                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                                <span>{{\Illuminate\Support\Carbon::parse($post->created_at)->format('j F')}}</span>
                                                <span>•</span>
                                                <span>{{ $post->upvotes_count }} {{__('votes')}}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                    </aside>
                @endif

            </div>
        </div>
        </div>
    </div>

    <!-- Post Creation Modal -->
    <div id="postModal" class="forum-modal hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 relative">
            <div id="modalBackdrop" class="forum-modal-backdrop absolute inset-0 z-0" onclick="closePostModal()" aria-hidden="true"></div>

            <div class="relative z-10 w-full max-w-5xl mx-auto my-12">
            <div class="forum-modal-panel p-6 md:p-8 overflow-y-auto max-h-[90vh]">
                <h3 class="forum-modal-title">{{ __('Create New Post') }}</h3>
                <form method="POST" action="{{route('forum.store')}}" enctype="multipart/form-data">
                    @csrf
                    <input name="title" type="text" placeholder="{{__('Post Title')}}"
                           class="forum-modal-input w-full p-4 mb-6 text-lg" required>

                    <!-- Image Upload Section -->
                    <div class="mb-6">
                        <label class="forum-modal-label">{{ __('Upload Image') }}</label>
                        <p class="text-zinc-500 text-xs mb-2">{{ __('JPEG, PNG, WebP, etc. Max 12 MB.') }}</p>
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="forum-modal-file-btn">
                                {{ __('Choose File') }}
                                <input type="file" id="image-upload" name="image" accept="image/*" class="hidden">
                            </label>
                            <span id="file-name" class="text-zinc-400 text-sm">{{ __('No file chosen') }}</span>
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
                        <trix-editor input="post-body" class="trix-content forum-modal-trix mt-2 min-h-[200px]"></trix-editor>
                    </div>

                    <div class="flex flex-wrap justify-end gap-3">
                        <button type="button" onclick="closePostModal()" class="forum-modal-btn-secondary">{{ __('Cancel') }}</button>
                        @if(auth()->user())
                            <button type="submit" class="forum-modal-btn-primary">{{ __('Post') }}</button>
                        @else
                            <a href="{{ route('login') }}" class="forum-modal-btn-primary text-center no-underline">{{ __('Login to Continue') }}</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editModal" class="forum-modal hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 relative">
            <div class="forum-modal-backdrop absolute inset-0 z-0" onclick="closeEditModal()" aria-hidden="true"></div>

            <div class="relative z-10 w-full max-w-5xl mx-auto my-12">
            <div class="forum-modal-panel p-6 md:p-8 overflow-y-auto max-h-[90vh]">
                <h3 class="forum-modal-title">{{ __('Edit Post') }}</h3>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input name="title" type="text" id="edit-title" placeholder="{{ __('Post Title') }}"
                           class="forum-modal-input w-full p-4 mb-6 text-lg" required>

                    <!-- Image Upload Section -->
                    <div class="mb-6">
                        <label class="forum-modal-label">{{ __('Upload Image') }}</label>
                        <p class="text-zinc-500 text-xs mb-2">{{ __('JPEG, PNG, WebP, etc. Max 12 MB.') }}</p>
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="forum-modal-file-btn">
                                {{ __('Choose File') }}
                                <input type="file" id="edit-image-upload" name="image" accept="image/*" class="hidden">
                            </label>
                            <span id="edit-file-name" class="text-zinc-400 text-sm">{{ __('No file chosen') }}</span>
                        </div>

                        <!-- Current Image -->
                        <div id="current-image-container" class="mt-4">
                            <p class="text-sm text-gray-400 mb-2">{{__('Current Image:')}}</p>
                            <div class="relative inline-block">
                                <img id="current-image" src="" alt="Current Image" class="max-h-40 rounded-lg border border-zinc-600">
                                <button type="button" id="remove-current-image" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                    ×
                                </button>
                            </div>
                        </div>

                        <!-- New Image Preview -->
                        <div id="edit-image-preview" class="mt-4 hidden">
                            <p class="text-sm text-gray-400 mb-2">{{__('New Image Preview:')}}</p>
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
                        <trix-editor input="edit-post-body" class="trix-content forum-modal-trix mt-2 min-h-[200px]"></trix-editor>
                    </div>

                    <div class="flex flex-wrap justify-end gap-3">
                        <button type="button" onclick="closeEditModal()" class="forum-modal-btn-secondary px-6 py-3">{{ __('Cancel') }}</button>
                        <button type="submit" class="forum-modal-btn-primary px-6 py-3">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="forum-modal hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 relative">
            <div class="forum-modal-backdrop absolute inset-0 z-0" onclick="closeDeleteModal()" aria-hidden="true"></div>
            <div class="relative z-10 w-full max-w-md mx-auto">
            <div class="forum-modal-panel forum-modal-panel--compact relative w-full p-6">
            <div class="flex flex-col items-center text-center">
                <div class="forum-delete-icon-wrap mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">{{ __('Delete Post') }}</h3>
                <p class="text-zinc-400 text-sm mb-6">{{ __('Are you sure you want to delete this post? This action cannot be undone.') }}</p>
                <div class="flex gap-3 w-full">
                    <button type="button" onclick="closeDeleteModal()" class="forum-modal-btn-secondary flex-1">{{ __('Cancel') }}</button>
                    <button type="button" id="confirmDeleteBtn" class="forum-modal-btn-danger flex-1">{{ __('Delete') }}</button>
                </div>
            </div>
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
            function openPostModal() {
                $('#postModal').removeClass('hidden');
                $('body').addClass('overflow-hidden');
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

                    $('.tab-button').each(function() {
                        $(this).toggleClass('is-active', $(this).data('tab') === tabName);
                    });
                }

                // Initialize with correct tab from URL
                const urlParams = new URLSearchParams(window.location.search);
                const activeTab = urlParams.get('tab') || 'newest';
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
                    const activeTab = urlParams.get('tab') || 'newest';
                    switchTab(activeTab);
                });

                // Upvote functionality
                $(document).on('click', '.upvote-btn', function() {
                    const button = $(this);
                    const forumId = button.data('forum-id');

                    $.ajax({
                        url: `/forum/${forumId}/upvote`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.error) {
                                showNotification(response.error, 'error');
                                return;
                            }

                            $(`.upvote-btn[data-forum-id="${forumId}"]`).each(function() {
                                $(this).toggleClass('is-active');
                            });

                            $(`.upvote-count[data-forum-id="${forumId}"]`).each(function() {
                                const $count = $(this);
                                $count.text(response.upvotes);
                                $count.toggleClass('is-active');
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
                    <div class="fixed top-4 end-4 px-4 py-2 ${colors[type]} text-white rounded-md shadow-md text-sm md:text-base flex items-center justify-between min-w-[200px]">
                        <span>${message}</span>
                        <button class="ms-2" onclick="$(this).parent().remove()">×</button>
                    </div>
                `);

                    $('body').append(notification);
                    setTimeout(() => notification.remove(), 3000);
                }
            });
        </script>
    @endpush

</x-app-layout>

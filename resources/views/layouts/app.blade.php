<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ session('dir', 'ltr') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://unpkg.com/trix@1.3.1/dist/trix.css">

        <!-- Before </body> -->
        <script src="https://unpkg.com/trix@1.3.1/dist/trix.js"></script>
        <!-- Scripts -->


        @vite(['resources/css/app.css', 'resources/js/app.js'])


        <!-- Styles -->
        @livewireStyles
        <script src="{{ asset('js/jquery.js') }}"></script>
        <style>
            /* Make all icons in the toolbar white */
            trix-toolbar .trix-button::before {
                filter: brightness(0) invert(1); /* turns dark SVG to white */
            }

                 /* Override Trix editor default styles */
             trix-editor {
                 border: 1px solid #52525b !important; /* zinc-600 */
                 border-radius: 0.5rem !important; /* rounded-lg */
                 background-color: #3f3f46 !important; /* zinc-700 */
                 color: white !important;
                 padding: 1rem !important; /* p-4 */
                 margin-bottom: 1.5rem !important; /* mb-6 */
                 font-size: 1.125rem !important; /* text-lg */
                 width: 100% !important; /* w-full */
             }

            trix-editor:focus {
                outline: none !important;
                border-color: transparent !important;
                box-shadow: 0 0 0 2px #ef4444 !important; /* focus:ring-2 focus:ring-red-500 */
            }

            /* Make text buttons white */
            trix-toolbar .trix-button {
                color: white !important;
                padding: 2px;
                border-radius: 10px;
                border: none !important;     /* Remove borders */
                margin-right: 0.5rem;        /* Add space between buttons */
            }

            /* Remove border on hover/active states too */
            trix-toolbar .trix-button:hover,
            trix-toolbar .trix-button.trix-active {
                border: none !important;
                box-shadow: none !important;
                background-color: hsla(0, 0%, 100%, .05);

            }

            /* Optional: Style dropdowns */
            trix-toolbar .trix-dialog {
                background-color: #4b5563 !important;
                color: white !important;
                border: none !important;
            }

            trix-toolbar .trix-input--dialog {
                background-color: #374151 !important;
                color: white !important;
                border: none !important;
            }
            trix-toolbar .trix-button:hover{
            }
            /* Optional: Add spacing between button groups if needed */
            trix-toolbar .trix-button-group {
                border: none;
            }

            /* Background for contrast */
            trix-toolbar {
                padding: 0.5rem; /* some padding around buttons */
            }
            .trix-content {
                padding: 1rem;
                min-height: 200px;
            }

            /* Style the file input button */
            input[type="file"]::file-selector-button {
                display: none;
            }
        </style>

    </head>
    <body class="{{ app()->getLocale() === 'ar' ? 'font-cairo' : 'font-changa' }}">
    @if(session('error'))
        <div class="fixed top-4 right-4 px-4 py-2 bg-red-600 text-white rounded">
            {{ session('error') }}
        </div>
    @endif
    <x-banner />

        <div class="min-h-screen bg-black">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow-xs">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        @stack('js')
    @include('layouts.footer')
    <!-- Add this before your closing </body> tag or in your layout file -->
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-transparent bg-opacity-70">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
            <h3 class="text-xl font-bold text-white mb-3">{{__('Delete Comment')}}</h3>
            <p class="text-gray-300 mb-6">{{__('Are you sure you want to delete this comment? This action cannot be undone.')}}</p>
            <div class="flex justify-end space-x-3">
                <button id="cancel-btn" class="px-4 cursor-pointer me-3 py-2 rounded-md bg-gray-600 hover:bg-gray-500 text-white transition-colors">
                    {{__('Cancel')}}
                </button>
                <button id="confirm-btn" class="px-4 cursor-pointer py-2 rounded-md bg-red-600 hover:bg-red-500 text-white transition-colors">
                    {{__('Delete')}}
                </button>
            </div>
        </div>
    </div>
    </body>
</html>

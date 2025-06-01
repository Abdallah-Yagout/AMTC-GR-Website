<x-app-layout>
    <section class="container mx-auto px-4 py-8">
        <!-- Page Title -->
        <h1 class="text-white text-center text-4xl font-bold mb-12">{{__('News')}}</h1>

        <!-- News Grid -->
        <div class="flex flex-wrap justify-center gap-8">
            @foreach($news as $new)
                <div class="bg-secondary-100 rounded-2xl overflow-hidden shadow-lg w-full sm:w-[350px] flex flex-col">
                    <!-- News Image -->
                    <img src="{{ asset('storage') }}/{{ $new->image }}" alt="{{ $new->title }}"
                         class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">

                    <!-- News Content -->
                    <div class="p-5 flex-grow flex flex-col">
                        <!-- Date -->
                        <p class="text-primary text-sm font-semibold mb-2">
                            {{ \Carbon\Carbon::parse($new->date)->format('F j, Y') }}
                        </p>

                        <!-- Title -->
                        <h3 class="text-white text-lg font-bold mb-4 line-clamp-2">
                            {{ $new->title }}
                        </h3>

                        <!-- Read More Button -->
                        <div class="mt-auto">
                            <a href="{{ route('news.view', ['slug' => $new->slug]) }}"
                               class="bg-primary hover:bg-primary-100 text-white font-bold py-2 px-4 rounded-full block text-center transition duration-300">
                                {{__('Read More')}}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $news->links() }}
        </div>
    </section>
</x-app-layout>

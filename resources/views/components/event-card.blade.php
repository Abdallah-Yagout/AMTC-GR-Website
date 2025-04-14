@props(['date', 'title', 'location', 'image'])

<div class="bg-secondary-100 rounded-2xl overflow-hidden shadow-lg max-w-sm">
    <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-48 object-cover">

    <div class="p-5">
        <p class="text-primary text-sm font-semibold mb-1">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</p>
        <h3 class="text-white text-lg font-bold mb-1">{{ $title }}</h3>
        <p class="text-gray-400 text-sm mb-4">{{ $location }}</p>

        <a href="#" class="bg-primary hover:bg-primary-100 text-white font-bold py-2 px-4 rounded-full block text-center transition duration-300">
          {{__('Register Now')}}
        </a>
    </div>
</div>

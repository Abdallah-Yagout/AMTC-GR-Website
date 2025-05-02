@props(['date', 'title', 'location', 'id'])

<div class="bg-secondary-100 rounded-2xl overflow-hidden shadow-lg max-w-sm">
    <div class="p-5">
        <p class="text-primary text-sm font-semibold mb-1">
            {{ \Carbon\Carbon::parse($date)->translatedFormat('F j, Y') }}
        </p>

        <h3 class="text-white text-lg font-bold mb-1">{{ $title }}</h3>

        @if(is_array($location))
            <div class="text-gray-400 text-sm mb-4 flex flex-wrap gap-1">
                @foreach($location as $loc)
                    <span class="inline-flex items-center after:content-[','] last:after:content-[''] after:mr-1">
                        {{ __($loc) }}
                    </span>
                @endforeach
            </div>
        @else
            <p class="text-gray-400 text-sm mb-4">{{ __($location) }}</p>
        @endif

        <a href="{{route('tournament.apply',['id'=>$id])}}" class="bg-primary hover:bg-primary-100 text-white font-bold py-2 px-4 rounded-full block text-center transition duration-300">
            {{__('Register Now')}}
        </a>
    </div>
</div>

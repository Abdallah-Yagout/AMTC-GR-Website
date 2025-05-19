<div id="countdown" class="bg-secondary text-white py-8 sm:py-12 px-4 sm:px-6 text-center" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <h2 class="text-xl sm:text-2xl md:text-3xl font-semibold mb-1">{{ __('Next Race Event') }}</h2>
    <p class="text-gray-400 text-xs sm:text-sm mb-6 sm:mb-10">GR Supra GT Cup 2025 - {{ __('Round') }} 3</p>

    <div class="flex justify-center gap-4 sm:gap-6 md:gap-10 text-white font-bold text-3xl sm:text-4xl md:text-5xl">
        @foreach(['days', 'hours', 'minutes', 'seconds'] as $unit)
            <div class="flex flex-col items-center">
                <div class="relative w-12 sm:w-14 md:w-16 h-14 sm:h-16 md:h-20 overflow-hidden">
                    <div id="{{ $unit }}-flip" class="absolute inset-0 flex items-center justify-center text-white transition-all duration-300 ease-in-out transform scale-100">
                        00
                    </div>
                </div>
                <div class="text-xs sm:text-sm mt-1 sm:mt-2 tracking-widest uppercase text-white/80">
                    @if(app()->getLocale() === 'ar')
                        {{ __(ucfirst($unit)) }}
                    @else
                        {{ strtoupper($unit) }}
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

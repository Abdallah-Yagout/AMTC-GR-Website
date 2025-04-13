<x-app-layout>
    <!-- Hero Section -->
    <section class="relative flex items-start overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('img/gazo_car.png') }}"
                 alt="Toyota Gazoo Racing E-Sports Background"
                 class="w-full h-auto md:h-screen object-cover opacity-70">
            <div class="absolute inset-0 bg-black/50"></div>
        </div>

        <div class="relative z-10 max-w-4xl p-8 md:p-20">
            <h1 class="text-white font-bold text-4xl md:text-6xl leading-tight tracking-wide font-sans uppercase">
                {{__('Welcome to')}} <br>
                <span class="text-primary">{{__('Toyota Gazoo Racing')}}</span><br>
                <span class="text-white">{{__('E-Sports')}}</span>
            </h1>
            <p class="text-white text-lg mt-6">
                {{__("Experience the thrill of virtual racing with the world's most")}} <br class="hidden md:inline">
                {{__('competitive e-motorsport platform')}}
            </p>
            <a href="#"
               class="inline-block bg-primary hover:bg-primary-100 text-white font-bold mt-8 px-6 py-3 rounded-full transition-all duration-300 transform hover:scale-105">
                ▶ {{(__('Watch Introduction'))}}
            </a>
        </div>
    </section>


@if ($upcoming_event)
        <section class="px-4 bg-secondary sm:px-6">
            <x-countdown :date="$upcoming_event->date" />
        </section>
        @push('js')
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const targetDate = new Date("{{ \Carbon\Carbon::parse($upcoming_event->date)->toIso8601String() }}").getTime();

                    function animateChange(id, value) {
                        const el = document.getElementById(id + "-flip");

                        // Skip if unchanged
                        if (el.textContent === value) return;

                        // Animate out
                        el.classList.add("translate-y-full", "opacity-0", "scale-90");

                        setTimeout(() => {
                            el.textContent = value;

                            // Reset position and animate in
                            el.classList.remove("translate-y-full", "opacity-0", "scale-90");
                            el.classList.add("translate-y-[-100%]", "opacity-0", "scale-90");

                            requestAnimationFrame(() => {
                                el.classList.remove("translate-y-[-100%]");
                                el.classList.add("translate-y-0", "opacity-100", "scale-100");
                            });
                        }, 200);
                    }

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const distance = targetDate - now;

                        if (distance < 0) return;

                        const days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                        const hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                        const minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        const seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');

                        animateChange("days", days);
                        animateChange("hours", hours);
                        animateChange("minutes", minutes);
                        animateChange("seconds", seconds);
                    }

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                });
            </script>
        @endpush
    @endif

    <!-- Upcoming Events Section -->
    <section class="bg-black py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl text-white font-bold text-center mb-8 sm:mb-12">
                {{__('Upcoming Events')}}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($events as $event)
                    <x-event-card
                        :image="asset('storage/' . $event->image)"
                        :date="$event->date"
                        :title="$event->title"
                        :location="$event->location"
                        class="w-full"
                    />
                @endforeach
            </div>
        </div>
    </section>
    <section class="bg-[#111] text-white py-12 border-t border-[#17171A]">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-semibold text-center mb-10">Community Highlights</h2>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Card 1 -->
                <div class="flex items-start gap-4 bg-[#111] p-6 rounded-lg">
                    <img src="{{asset('img/image 11.png')}}" alt="User Image" class="w-12 h-12 rounded-full">
                    <div>
                        <h3 class="font-bold text-lg">Mohammed (Winner of GR Supra GT Cup Round 2)</h3>
                        <p class="text-sm text-gray-300 mt-1">
                            An incredible performance at Spa-Francorchamps secures Davidson’s second victory of the season.
                        </p>
                        <div class="flex text-xs text-gray-500 mt-2 space-x-4">
                            <span>2 days ago</span>
                            <span>2.4k 👁️</span>
                            <span>136 💬</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="flex items-start gap-4 bg-[#111] p-6 rounded-lg">
                    <img src="{{asset('img/image 12.png')}}" alt="User Image" class="w-12 h-12 rounded-full">
                    <div>
                        <h3 class="font-bold text-lg">Toni Breidinger</h3>
                        <p class="text-sm text-gray-300 mt-1">
                            Toyota Gazoo Racing announces new track pack featuring iconic Japanese circuits.
                        </p>
                        <div class="flex text-xs text-gray-500 mt-2 space-x-4">
                            <span>4 days ago</span>
                            <span>6.4k 👁️</span>
                            <span>56 💬</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</x-app-layout>

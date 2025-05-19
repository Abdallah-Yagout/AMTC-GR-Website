<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <!-- Hero Section -->
    <section class="relative flex items-start overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="<?php echo e(asset('img/gazo_car.png')); ?>"
                 alt="Toyota Gazoo Racing E-Sports Background"
                 class="w-full h-full md:h-screen object-cover">
            <div class="absolute inset-0 bg-black/50"></div>
        </div>

        <div class="relative z-10 max-w-4xl p-8 md:p-20">
            <h1 class="text-white font-bold text-4xl md:text-6xl leading-tight tracking-wide font-sans uppercase">
                <?php echo e(__('Welcome to')); ?> <br>
                <span class="text-primary"><?php echo e(__('Toyota Gazoo Racing')); ?></span><br>
                <span class="text-white"><?php echo e(__('E-Sports')); ?></span>
            </h1>
            <p class="text-white text-lg mt-6">
                <?php echo e(__("Experience the thrill of virtual racing with the world's most")); ?> <br class="hidden md:inline">
                <?php echo e(__('competitive e-motorsport platform')); ?>

            </p>
            <a href="#"
               class="inline-block bg-primary hover:bg-primary-100 text-white font-bold mt-8 px-6 py-3 rounded-full transition-all duration-300 transform hover:scale-105">
                ▶ <?php echo e((__('Watch Introduction'))); ?>

            </a>
        </div>
    </section>


<?php if($upcoming_event): ?>
        <section class="px-4 bg-secondary sm:px-6">
            <?php if (isset($component)) { $__componentOriginal9782705243572a99a094a5586943141f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9782705243572a99a094a5586943141f = $attributes; } ?>
<?php $component = App\View\Components\Countdown::resolve(['date' => $upcoming_event->date] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('countdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Countdown::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9782705243572a99a094a5586943141f)): ?>
<?php $attributes = $__attributesOriginal9782705243572a99a094a5586943141f; ?>
<?php unset($__attributesOriginal9782705243572a99a094a5586943141f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9782705243572a99a094a5586943141f)): ?>
<?php $component = $__componentOriginal9782705243572a99a094a5586943141f; ?>
<?php unset($__componentOriginal9782705243572a99a094a5586943141f); ?>
<?php endif; ?>
        </section>
        <?php $__env->startPush('js'); ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const targetDate = new Date("<?php echo e(\Carbon\Carbon::parse($upcoming_event->date)->toIso8601String()); ?>").getTime();

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
        <?php $__env->stopPush(); ?>
    <?php endif; ?>

    <!-- Upcoming Events Section -->
    <section class="bg-black py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl text-white font-bold text-center mb-8 sm:mb-12">
                <?php echo e(__('Upcoming Tournaments')); ?>

            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <?php $__currentLoopData = $tournaments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tournament): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php if (isset($component)) { $__componentOriginal07bdbe031a4c57e4cd3488994f94e999 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal07bdbe031a4c57e4cd3488994f94e999 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.event-card','data' => ['image' => asset('storage/' . $tournament->image),'date' => $tournament->date,'title' => $tournament->title,'location' => $tournament->location,'id' => $tournament->id,'class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('event-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('storage/' . $tournament->image)),'date' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tournament->date),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tournament->title),'location' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tournament->location),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tournament->id),'class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal07bdbe031a4c57e4cd3488994f94e999)): ?>
<?php $attributes = $__attributesOriginal07bdbe031a4c57e4cd3488994f94e999; ?>
<?php unset($__attributesOriginal07bdbe031a4c57e4cd3488994f94e999); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal07bdbe031a4c57e4cd3488994f94e999)): ?>
<?php $component = $__componentOriginal07bdbe031a4c57e4cd3488994f94e999; ?>
<?php unset($__componentOriginal07bdbe031a4c57e4cd3488994f94e999); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <section class="bg-[#111] text-white py-12 border-t border-[#17171A]">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-semibold text-center mb-10"><?php echo e(__('Community Highlights')); ?></h2>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Card 1 -->
                <div class="flex items-start gap-4 bg-[#111] p-6 rounded-lg">
                    <img src="<?php echo e(asset('img/image 11.png')); ?>" alt="User Image" class="w-12 h-12 rounded-full">
                    <div>
                        <h3 class="font-bold text-lg"><?php echo e(__('Mohammed (Winner of GR Supra GT Cup Round 2)')); ?></h3>
                        <p class="text-sm text-gray-300 mt-1">
                            <?php echo e(__('An incredible performance at Spa-Francorchamps secures Davidson’s second victory of the season.')); ?>

                        </p>
                        <div class="flex text-xs text-gray-500 mt-2 space-x-4">
                            <span><?php echo e(__('2 days ago')); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="flex items-start gap-4 bg-[#111] p-6 rounded-lg">
                    <img src="<?php echo e(asset('img/image 12.png')); ?>" alt="User Image" class="w-12 h-12 rounded-full">
                    <div>
                        <h3 class="font-bold text-lg"><?php echo e(__('Toni Breidinger')); ?></h3>
                        <p class="text-sm text-gray-300 mt-1">
                            <?php echo e(__('Toyota Gazoo Racing announces new track pack featuring iconic Japanese circuits.')); ?>

                        </p>
                        <div class="flex text-xs text-gray-500 mt-2 space-x-4">
                            <span><?php echo e(__('4 days ago')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/u968167241/domains/gryemen.com/public_html/resources/views/home.blade.php ENDPATH**/ ?>
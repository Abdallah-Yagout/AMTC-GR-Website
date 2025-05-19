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
    <section class="bg-primary-200 px-10 py-10 text-white">
        <h1 class="text-3xl font-bold mb-2">Let the Race Begin!</h1>
        <p class="text-lg">Check out detailed event schedules and gear up for action in Mukalla, Aden, and Sana'a</p>
    </section>

    <section x-data="{ tab: '<?php echo e(array_key_first($tournamentsByLocation)); ?>' }" class="px-10 bg-black py-6">
        <!-- Tab Headers -->
        <div class="flex space-x-4 mb-6">
            <?php $__currentLoopData = $tournamentsByLocation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location => $tournaments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button
                    @click="tab = '<?php echo e($location); ?>'"
                    :class="tab === '<?php echo e($location); ?>'
        ? 'border-b-4 border-primary cursor-pointer text-red-600 font-semibold'
        : 'text-white cursor-pointer border-transparent'"
                    class="py-2 px-4 transition">
                    <?php echo e(ucfirst($location)); ?>

                </button>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Tab Contents -->
        <?php $__currentLoopData = $tournamentsByLocation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location => $tournaments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div x-show="tab === '<?php echo e($location); ?>'" class="space-y-3 sm:space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $tournaments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tournament): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 sm:p-4 shadow-sm  bg-secondary-100 text-white rounded-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4">
                            <div class="flex-1">
                                <h3 class="text-lg sm:text-xl font-semibold line-clamp-2"><?php echo e($tournament->title); ?></h3>
                                <p class="text-xs sm:text-sm text-gray-600 text-gray-400 mt-1 line-clamp-2 sm:line-clamp-3">
                                    <?php echo e($tournament->description); ?>

                                </p>
                                <p class="text-xs sm:text-sm mt-2 text-gray-300">
                                    Date: <?php echo e(\Carbon\Carbon::parse($tournament->date)->format('F j, Y')); ?>

                                </p>
                            </div>
                            <div class="sm:w-auto w-full">

                                <?php if($tournament->location_participant_count >= $tournament->number_of_players): ?>
                                    <span class="block text-center sm:inline-block text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-1 bg-gray-400 text-white rounded cursor-not-allowed">
        <?php echo e(__('Full')); ?>

    </span>
                                <?php else: ?>
                                    <a href="<?php echo e(route('tournament.apply', ['id' => $tournament->id])); ?>"
                                       class="block text-center sm:inline-block text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-1 bg-red-600 text-white rounded hover:bg-red-700 transition whitespace-nowrap">
                                        <?php echo e(__('Register')); ?>

                                    </a>
                                <?php endif; ?>


                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm sm:text-base text-gray-600 text-gray-400 p-4 text-center">
                        No tournaments available in <?php echo e(ucfirst($location)); ?>.
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH /home/u968167241/domains/gryemen.com/public_html/resources/views/tournament/index.blade.php ENDPATH**/ ?>
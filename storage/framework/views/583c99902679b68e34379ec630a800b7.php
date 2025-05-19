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
    <section class="dark:bg-primary-200 p-10 dark:text-white">
        <h1 class="text-3xl font-bold mb-2"><?php echo e(__('Who\'s in the lead?')); ?></h1>
        <p class="text-lg"><?php echo e(__('See who\'s ahead, who\'s catching up, and who\'s next to take the podium')); ?></p>
    </section>

    <section class="container mx-auto px-4 py-8">
        <?php if(isset($finalTournament)): ?>

            <!-- Two-column layout when final leaderboard exists -->
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Final Tournament Leaderboard Column -->
                <div class="lg:w-1/2">
                    <div class="bg-secondary-100 p-6 rounded-lg shadow-lg">
                        <h1 class="text-white text-3xl font-bold mb-4"><?php echo e($finalTournament->title); ?> - <?php echo e(__('Final Results')); ?></h1>

                        <!-- Header - Hidden on small screens -->
                        <div class="hidden sm:grid grid-cols-12 gap-4 px-2 py-2 border-b border-gray-700">
                            <div class="col-span-1 text-white font-bold text-center">#</div>
                            <div class="col-span-6 text-white font-bold"><?php echo e(__('Driver')); ?></div>
                            <div class="col-span-2 text-white font-bold text-center"><?php echo e(__('Time')); ?></div>
                        </div>

                        <?php $__currentLoopData = $finalLeaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $minutes = floor($participant->time_taken / 60);
                                $seconds = $participant->time_taken % 60;
                            ?>

                            <div class="grid grid-cols-12 gap-2 md:gap-4 items-center border-b border-gray-700 p-2 md:p-3">
                                <!-- Position -->
                                <div class="col-span-2 md:col-span-1 text-white text-lg md:text-xl font-bold text-center">
                                    <span class="sm:hidden text-sm mr-1">#</span>
                                    <?php echo e($participant->position); ?>

                                </div>

                                <!-- Driver -->
                                <div class="col-span-7 md:col-span-6 flex items-center gap-2 md:gap-3">
                                    <img src="<?php echo e($participant->user->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name)); ?>"
                                         alt="<?php echo e($participant->user->name); ?>"
                                         class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border border-white">
                                    <span class="text-white text-sm md:text-base truncate">
                                        <?php echo e($participant->user->name); ?>

                                    </span>
                                </div>

                                <!-- Time -->
                                <div class="col-span-3 md:col-span-2 text-green-400 text-sm md:text-lg font-bold text-right md:text-center">
                                    <span class="sm:hidden text-xs text-white mr-1">Time:</span>
                                    <?php echo e(sprintf('%d:%06.3f', $minutes, $seconds)); ?>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Season Leaderboard Column -->
                <div class="lg:w-1/2">
                    <div class="bg-secondary-100 p-6 rounded-lg shadow-lg">
                        <h1 class="text-white text-3xl font-bold mb-4"><?php echo e(__('Season Leaderboard')); ?></h1>

                        <!-- Location Tabs -->
                        <div x-data="{ tab: '<?php echo e(array_key_first($season_leaderboard->toArray())); ?>' }">
                            <div class="flex overflow-x-auto pb-2 mb-6 scrollbar-hide">
                                <?php $__currentLoopData = $season_leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location => $participants): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button
                                        @click="tab = '<?php echo e($location); ?>'"
                                        :class="tab === '<?php echo e($location); ?>'
                                            ? 'border-b-4 border-primary text-red-600 font-semibold'
                                            : 'text-white border-transparent'"
                                        class="flex-shrink-0 py-2 px-4 transition whitespace-nowrap">
                                        <?php echo e(ucfirst($location)); ?>

                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <!-- Leaderboard Per Location -->
                            <?php $__currentLoopData = $season_leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location => $participants): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div x-show="tab === '<?php echo e($location); ?>'" class="space-y-2 md:space-y-4">
                                    <h2 class="text-white text-xl md:text-2xl font-bold mb-2 md:mb-4">
                                        <?php echo e(ucfirst($location)); ?> Leaderboard
                                    </h2>

                                    <!-- Header -->
                                    <div class="hidden sm:grid grid-cols-12 gap-2 md:gap-4 px-2 py-2 border-b border-gray-700">
                                        <div class="col-span-1 text-white font-bold text-center">#</div>
                                        <div class="col-span-6 text-white font-bold"><?php echo e(__('Driver')); ?></div>
                                        <div class="col-span-2 text-white font-bold text-center"><?php echo e(__('Time')); ?></div>
                                        <div class="col-span-3 text-white font-bold text-center"><?php echo e(__('+Diff')); ?></div>
                                    </div>

                                    <?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $minutes = floor($participant->time_taken / 60);
                                            $seconds = $participant->time_taken % 60;
                                            $diff = $participant->time_taken - $participants[0]->time_taken;
                                        ?>

                                        <div class="grid grid-cols-12 gap-2 md:gap-4 items-center border-b border-gray-700 p-2 md:p-3">
                                            <!-- Position -->
                                            <div class="col-span-2 md:col-span-1 text-white text-lg font-bold text-center">
                                                <span class="sm:hidden text-sm mr-1">#</span>
                                                <?php echo e($participant->position); ?>

                                            </div>

                                            <!-- Driver -->
                                            <div class="col-span-6 md:col-span-6 flex items-center gap-2 md:gap-3">
                                                <img src="<?php echo e($participant->user->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name)); ?>"
                                                     alt="<?php echo e($participant->user->name); ?>"
                                                     class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border border-white">
                                                <span class="text-white text-sm md:text-base truncate">
                                                    <?php echo e($participant->user->name); ?>

                                                </span>
                                            </div>

                                            <!-- Time -->
                                            <div class="col-span-2 md:col-span-2 text-green-400 text-sm md:text-base font-bold text-right md:text-center">
                                                <span class="sm:hidden text-xs text-white mr-1">Time:</span>
                                                <?php echo e(sprintf('%d:%06.3f', $minutes, $seconds)); ?>

                                            </div>

                                            <!-- Diff -->
                                            <div class="col-span-2 md:col-span-3 text-xs md:text-sm font-bold text-right md:text-center <?php echo e($diff > 0 ? 'text-red-400' : 'text-green-400'); ?>">
                                                <span class="sm:hidden text-xs text-white mr-1">Diff:</span>
                                                <?php echo e($diff > 0 ? '+' : ''); ?><?php echo e(number_format($diff, 3)); ?>s
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Centered single column when no final leaderboard -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-secondary-100 p-6 rounded-lg shadow-lg">
                    <h1 class="text-white text-3xl font-bold mb-4"><?php echo e(__('Season Leaderboard')); ?></h1>

                    <!-- Location Tabs -->
                    <div x-data="{ tab: '<?php echo e(array_key_first($season_leaderboard->toArray())); ?>' }">
                        <div class="flex overflow-x-auto pb-2 mb-6 scrollbar-hide">
                            <?php $__currentLoopData = $season_leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location => $participants): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button
                                    @click="tab = '<?php echo e($location); ?>'"
                                    :class="tab === '<?php echo e($location); ?>'
                                        ? 'border-b-4 border-primary text-red-600 font-semibold'
                                        : 'text-white border-transparent'"
                                    class="flex-shrink-0 py-2 px-4 transition whitespace-nowrap">
                                    <?php echo e(ucfirst($location)); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Leaderboard Per Location -->
                        <?php $__currentLoopData = $season_leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location => $participants): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div x-show="tab === '<?php echo e($location); ?>'" class="space-y-2 md:space-y-4">
                                <h2 class="text-white text-xl md:text-2xl font-bold mb-2 md:mb-4">
                                    <?php echo e(ucfirst($location)); ?> Leaderboard
                                </h2>

                                <!-- Header -->
                                <div class="hidden sm:grid grid-cols-12 gap-2 md:gap-4 px-2 py-2 border-b border-gray-700">
                                    <div class="col-span-1 text-white font-bold text-center">#</div>
                                    <div class="col-span-6 text-white font-bold"><?php echo e(__('Driver')); ?></div>
                                    <div class="col-span-2 text-white font-bold text-center"><?php echo e(__('Time')); ?></div>
                                    <div class="col-span-3 text-white font-bold text-center"><?php echo e(__('+Diff')); ?></div>
                                </div>

                                <?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $minutes = floor($participant->time_taken / 60);
                                        $seconds = $participant->time_taken % 60;
                                        $diff = $participant->time_taken - $participants[0]->time_taken;
                                    ?>

                                    <div class="grid grid-cols-12 gap-2 md:gap-4 items-center border-b border-gray-700 p-2 md:p-3">
                                        <!-- Position -->
                                        <div class="col-span-2 md:col-span-1 text-white text-lg font-bold text-center">
                                            <span class="sm:hidden text-sm mr-1">#</span>
                                            <?php echo e($participant->position); ?>

                                        </div>

                                        <!-- Driver -->
                                        <div class="col-span-6 md:col-span-6 flex items-center gap-2 md:gap-3">
                                            <img src="<?php echo e($participant->user->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name)); ?>"
                                                 alt="<?php echo e($participant->user->name); ?>"
                                                 class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border border-white">
                                            <span class="text-white text-sm md:text-base truncate">
                                                <?php echo e($participant->user->name); ?>

                                            </span>
                                        </div>

                                        <!-- Time -->
                                        <div class="col-span-2 md:col-span-2 text-green-400 text-sm md:text-base font-bold text-right md:text-center">
                                            <span class="sm:hidden text-xs text-white mr-1">Time:</span>
                                            <?php echo e(sprintf('%d:%06.3f', $minutes, $seconds)); ?>

                                        </div>

                                        <!-- Diff -->
                                        <div class="col-span-2 md:col-span-3 text-xs md:text-sm font-bold text-right md:text-center <?php echo e($diff > 0 ? 'text-red-400' : 'text-green-400'); ?>">
                                            <span class="sm:hidden text-xs text-white mr-1">Diff:</span>
                                            <?php echo e($diff > 0 ? '+' : ''); ?><?php echo e(number_format($diff, 3)); ?>s
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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
<?php /**PATH /home/u968167241/domains/gryemen.com/public_html/resources/views/leaderboard/index.blade.php ENDPATH**/ ?>
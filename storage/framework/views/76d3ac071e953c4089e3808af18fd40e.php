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
    <div class="flex flex-col items-center pt-6 sm:pt-0">
        <section class="w-full bg-primary-200 px-6 sm:px-20 py-4 text-white">
            <h1 class="text-3xl font-bold"><?php echo e(__('Join the Competition')); ?></h1>
            <p class="mb-8"><?php echo e(__('Submit your entry and monitor your progress — full speed ahead!')); ?></p>
        </section>
    </div>

    <div class=" text-white py-12 px-6 md:px-20">
        <div class="bg-secondary rounded-lg p-8 w-full max-w-5xl border-1 border-[#999999] mx-auto">
            <h2 class="text-xl text-center font-bold mb-6"><?php echo e(__('Participant Application')); ?></h2>
            <form action="<?php echo e(route('tournament.submit')); ?>" method="post">
                <input type="hidden" name="tournamentId" value="<?php echo e($tournament->id); ?>">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-white"><?php echo e(__('Name')); ?></label>
                        <input
                            type="text"
                            name="name"
                            required
                            disabled
                            value="<?php echo e(auth()->check() ? auth()->user()->name : old('name')); ?>"
                            placeholder="<?php echo e(__('Enter your name')); ?>"
                            class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent"
                        />
                    </div>

                </div>

                <!-- Combined row for Gender, City, Address, and Phone -->
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mt-4">
                    <!-- Gender -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-white"><?php echo e(__('Gender')); ?></label>
                        <select
                            required
                            name="gender"
                            class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent bg-transparent focus:bg-transparent text-gray-400 appearance-none hover:bg-transparent transition-colors duration-200">
                            <option value=""><?php echo e(__('Select')); ?></option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <!-- City -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-white"><?php echo e(__('City')); ?></label>

                        <select
                            name="city"
                            required
                            class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent focus:bg-transparent text-gray-400 appearance-none hover:bg-transparent transition-colors duration-200">
                            <option value=""><?php echo e(__('Select')); ?></option>
                            <?php $__currentLoopData = $tournament->location; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($city); ?>"><?php echo e($city); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>



                    <!-- Phone Number -->
                    <div class="space-y-1 md:col-span-2">
                        <label class="block text-sm font-medium text-white"><?php echo e(__('Phone')); ?></label>
                        <input
                            type="text"
                            name="phone"
                            required
                            disabled
                            value="<?php echo e(auth()->check() ? auth()->user()->phone : old('phone')); ?>"
                            placeholder="<?php echo e(__('Phone number')); ?>"
                            class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent"
                        />
                    </div>
                </div>

                <!-- Email and Racing Experience -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- Email Address -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-white"><?php echo e(__('Email Address')); ?></label>
                        <input
                            type="email"
                            name="email"
                            required
                            disabled
                            value="<?php echo e(auth()->check() ? auth()->user()->email : old('email')); ?>"
                            placeholder="<?php echo e(__('Enter your email address')); ?>"
                            class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent"
                        />
                    </div>


                </div>

                <button type="submit" class="mt-6 bg-red-600 hover:bg-red-700 px-6 py-2 rounded"><?php echo e(__('Submit')); ?></button>
            </form>
        </div>
    </div>
    <section class="bg-secondary mt-10 w-full py-8 px-4 rounded-lg text-white mx-auto">
        <h1 class="text-center text-3xl font-bold mb-12"><?php echo e(__('Application Analytics')); ?></h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Applications -->
            <div class="bg-black p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-2xl font-medium  text-gray-300"><?php echo e(__('Total Application')); ?></p>
                    <?php if (isset($component)) { $__componentOriginal606b6d7eddc2e418f11096356be15e19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal606b6d7eddc2e418f11096356be15e19 = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Icon::resolve(['name' => 'heroicon-o-document'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Icon::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal606b6d7eddc2e418f11096356be15e19)): ?>
<?php $attributes = $__attributesOriginal606b6d7eddc2e418f11096356be15e19; ?>
<?php unset($__attributesOriginal606b6d7eddc2e418f11096356be15e19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal606b6d7eddc2e418f11096356be15e19)): ?>
<?php $component = $__componentOriginal606b6d7eddc2e418f11096356be15e19; ?>
<?php unset($__componentOriginal606b6d7eddc2e418f11096356be15e19); ?>
<?php endif; ?>
                </div>
                <p class="text-2xl font-bold mb-1">1,234</p>
            </div>

            <!-- Accepted Racers -->
            <div class="bg-black p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-2xl font-medium text-gray-300"><?php echo e(__('Accepted Racers')); ?></p>
                    <?php if (isset($component)) { $__componentOriginal606b6d7eddc2e418f11096356be15e19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal606b6d7eddc2e418f11096356be15e19 = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Icon::resolve(['name' => 'heroicon-o-user'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Icon::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal606b6d7eddc2e418f11096356be15e19)): ?>
<?php $attributes = $__attributesOriginal606b6d7eddc2e418f11096356be15e19; ?>
<?php unset($__attributesOriginal606b6d7eddc2e418f11096356be15e19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal606b6d7eddc2e418f11096356be15e19)): ?>
<?php $component = $__componentOriginal606b6d7eddc2e418f11096356be15e19; ?>
<?php unset($__componentOriginal606b6d7eddc2e418f11096356be15e19); ?>
<?php endif; ?>
                </div>
                <p class="text-2xl font-bold mb-1">856</p>
            </div>

            <!-- Active Events -->
            <div class="bg-black p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-2xl font-medium text-gray-300"><?php echo e(__('Active Events')); ?></p>
                    <?php if (isset($component)) { $__componentOriginal606b6d7eddc2e418f11096356be15e19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal606b6d7eddc2e418f11096356be15e19 = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Icon::resolve(['name' => 'heroicon-o-calendar'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Icon::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal606b6d7eddc2e418f11096356be15e19)): ?>
<?php $attributes = $__attributesOriginal606b6d7eddc2e418f11096356be15e19; ?>
<?php unset($__attributesOriginal606b6d7eddc2e418f11096356be15e19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal606b6d7eddc2e418f11096356be15e19)): ?>
<?php $component = $__componentOriginal606b6d7eddc2e418f11096356be15e19; ?>
<?php unset($__componentOriginal606b6d7eddc2e418f11096356be15e19); ?>
<?php endif; ?>
                </div>
                <p class="text-2xl font-bold mb-1">12</p>
                <span class="text-xs text-gray-400"><?php echo e(__('Yemen')); ?></span>
            </div>

            <!-- Viewers -->
            <div class="bg-black p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-2xl font-medium text-gray-300"><?php echo e(__('Viewers')); ?></p>
                    <?php if (isset($component)) { $__componentOriginal606b6d7eddc2e418f11096356be15e19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal606b6d7eddc2e418f11096356be15e19 = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Icon::resolve(['name' => 'heroicon-o-eye'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Icon::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-7 h-7 text-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal606b6d7eddc2e418f11096356be15e19)): ?>
<?php $attributes = $__attributesOriginal606b6d7eddc2e418f11096356be15e19; ?>
<?php unset($__attributesOriginal606b6d7eddc2e418f11096356be15e19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal606b6d7eddc2e418f11096356be15e19)): ?>
<?php $component = $__componentOriginal606b6d7eddc2e418f11096356be15e19; ?>
<?php unset($__componentOriginal606b6d7eddc2e418f11096356be15e19); ?>
<?php endif; ?>
                </div>
                <p class="text-2xl font-bold mb-1">20,656</p>
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
<?php /**PATH /home/u968167241/domains/gryemen.com/public_html/resources/views/tournament/apply.blade.php ENDPATH**/ ?>
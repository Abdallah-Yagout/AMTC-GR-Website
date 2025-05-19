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
    <section class="container mx-auto px-4 py-8">
        <!-- Page Title -->
        <h1 class="text-white text-center text-4xl font-bold mb-12"><?php echo e(__('News')); ?></h1>

        <!-- News Grid -->
        <div class="flex flex-wrap justify-center gap-8">
            <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $new): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-secondary-100 rounded-2xl overflow-hidden shadow-lg w-full sm:w-[350px] flex flex-col">
                    <!-- News Image -->
                    <img src="<?php echo e(asset('storage')); ?>/<?php echo e($new->image); ?>" alt="<?php echo e($new->title); ?>"
                         class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">

                    <!-- News Content -->
                    <div class="p-5 flex-grow flex flex-col">
                        <!-- Date -->
                        <p class="text-primary text-sm font-semibold mb-2">
                            <?php echo e(\Carbon\Carbon::parse($new->date)->format('F j, Y')); ?>

                        </p>

                        <!-- Title -->
                        <h3 class="text-white text-lg font-bold mb-4 line-clamp-2">
                            <?php echo e($new->title); ?>

                        </h3>

                        <!-- Read More Button -->
                        <div class="mt-auto">
                            <a href="<?php echo e(route('news.view', ['slug' => $new->slug])); ?>"
                               class="bg-primary hover:bg-primary-100 text-white font-bold py-2 px-4 rounded-full block text-center transition duration-300">
                                <?php echo e(__('Read More')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            <?php echo e($news->links()); ?>

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
<?php /**PATH /home/u968167241/domains/gryemen.com/public_html/resources/views/news/index.blade.php ENDPATH**/ ?>
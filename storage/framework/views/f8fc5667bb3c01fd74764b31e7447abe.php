<div id="countdown" class="bg-secondary text-white py-8 sm:py-12 px-4 sm:px-6 text-center" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">
    <h2 class="text-xl sm:text-2xl md:text-3xl font-semibold mb-1"><?php echo e(__('Next Race Event')); ?></h2>
    <p class="text-gray-400 text-xs sm:text-sm mb-6 sm:mb-10">GR Supra GT Cup 2025 - <?php echo e(__('Round')); ?> 3</p>

    <div class="flex justify-center gap-4 sm:gap-6 md:gap-10 text-white font-bold text-3xl sm:text-4xl md:text-5xl">
        <?php $__currentLoopData = ['days', 'hours', 'minutes', 'seconds']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex flex-col items-center">
                <div class="relative w-12 sm:w-14 md:w-16 h-14 sm:h-16 md:h-20 overflow-hidden">
                    <div id="<?php echo e($unit); ?>-flip" class="absolute inset-0 flex items-center justify-center text-white transition-all duration-300 ease-in-out transform scale-100">
                        00
                    </div>
                </div>
                <div class="text-xs sm:text-sm mt-1 sm:mt-2 tracking-widest uppercase text-white/80">
                    <?php if(app()->getLocale() === 'ar'): ?>
                        <?php echo e(__(ucfirst($unit))); ?>

                    <?php else: ?>
                        <?php echo e(strtoupper($unit)); ?>

                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH /home/u968167241/domains/gryemen.com/public_html/resources/views/components/countdown.blade.php ENDPATH**/ ?>
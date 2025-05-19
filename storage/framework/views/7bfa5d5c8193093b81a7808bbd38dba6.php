<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['date', 'title', 'location', 'image','id']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['date', 'title', 'location', 'image','id']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="bg-secondary-100 rounded-2xl overflow-hidden shadow-lg max-w-sm">
    <img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>" class="w-full h-48 object-cover">

    <div class="p-5">
        <p class="text-primary text-sm font-semibold mb-1">
            <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('F j, Y')); ?>

        </p>

        <h3 class="text-white text-lg font-bold mb-1"><?php echo e($title); ?></h3>

        <?php if(is_array($location)): ?>
            <div class="text-gray-400 text-sm mb-4 flex flex-wrap gap-1">
                <?php $__currentLoopData = $location; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="inline-flex items-center after:content-[','] last:after:content-[''] after:mr-1">
                        <?php echo e(__($loc)); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p class="text-gray-400 text-sm mb-4"><?php echo e(__($location)); ?></p>
        <?php endif; ?>

        <a href="<?php echo e(route('tournament.apply',['id'=>$id])); ?>" class="bg-primary hover:bg-primary-100 text-white font-bold py-2 px-4 rounded-full block text-center transition duration-300">
            <?php echo e(__('Register Now')); ?>

        </a>
    </div>
</div>
<?php /**PATH /home/u968167241/domains/gryemen.com/public_html/resources/views/components/event-card.blade.php ENDPATH**/ ?>
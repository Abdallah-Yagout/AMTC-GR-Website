<div class="bg-zinc-900 rounded p-4 flex flex-col md:flex-row justify-between gap-4">
    <div class="flex items-center gap-4">
        <div class="border rounded border-gray-50 w-20 flex items-center justify-between px-4 py-2">
            <img src="<?php echo e(asset('img/arrow.png')); ?>" alt="Upvote">
            <span class="font-semibold text-red-500"><?php echo e($forum->upvotes); ?></span>
        </div>
        <div>
            <p class="font-semibold mb-1"><?php echo e(__('An incredible performance at Spa-Francorchamps secures Davidson’s second victory of the season.')); ?></p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
                <img src="<?php echo e(asset('storage').'/'.$forum->user->profile_photo_path); ?>" alt="User Avatar" class="w-6 h-6 rounded-full object-cover" />
                <p><span class="text-primary font-medium"><?php echo e($forum->user->name); ?></span> • <?php echo e($forum->created_at->diffForHumans()); ?></p>
            </div>
        </div>
    </div>
    <button class="text-gray-400 hover:text-white self-start md:self-center">🔗</button>
</div>
<?php /**PATH /home/u968167241/domains/gryemen.com/public_html/resources/views/components/forum-card.blade.php ENDPATH**/ ?>
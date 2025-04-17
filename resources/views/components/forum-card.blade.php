<div class="bg-zinc-900 rounded p-4 flex flex-col md:flex-row justify-between gap-4">
    <div class="flex items-center gap-4">
        <div class="border rounded border-gray-50 w-20 flex items-center justify-between px-4 py-2">
            <img src="{{ asset('img/arrow.png') }}" alt="Upvote">
            <span class="font-semibold text-red-500">{{$forum->upvotes}}</span>
        </div>
        <div>
            <p class="font-semibold mb-1">{{__('An incredible performance at Spa-Francorchamps secures Davidson’s second victory of the season.')}}</p>
            <div class="flex items-center space-x-2 text-sm text-gray-400">
                <img src="{{ asset('img/image 11.png') }}" alt="User Avatar" class="w-6 h-6 rounded-full object-cover" />
                <p><span class="text-primary font-medium">{{$forum->user->name}}</span> • {{$forum->created_at->diffForHumans()}}</p>
            </div>
        </div>
    </div>
    <button class="text-gray-400 hover:text-white self-start md:self-center">🔗</button>
</div>

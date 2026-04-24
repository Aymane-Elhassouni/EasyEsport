@props(['type' => 'card', 'lines' => 3])

<div class="animate-pulse">
    @if($type === 'card')
        <div class="glass rounded-2xl p-6 h-full flex flex-col gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-200 dark:bg-white/10"></div>
                <div class="space-y-2 flex-1">
                    <div class="h-4 bg-gray-200 dark:bg-white/10 rounded w-1/3"></div>
                    <div class="h-3 bg-gray-200 dark:bg-white/5 rounded w-1/4"></div>
                </div>
            </div>
            <div class="space-y-2 mt-4">
                @for($i = 0; $i < $lines; $i++)
                    <div class="h-2 bg-gray-200 dark:bg-white/5 rounded w-full"></div>
                @endfor
                <div class="h-2 bg-gray-200 dark:bg-white/5 rounded w-2/3"></div>
            </div>
        </div>
    @elseif($type === 'text')
        <div class="space-y-2 w-full">
            <div class="h-4 bg-gray-200 dark:bg-white/10 rounded w-3/4"></div>
            <div class="h-4 bg-gray-200 dark:bg-white/10 rounded w-1/2"></div>
        </div>
    @elseif($type === 'avatar')
        <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-white/10"></div>
    @endif
</div>

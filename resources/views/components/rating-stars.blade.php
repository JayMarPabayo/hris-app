@php
    $fullStars = floor($rating);
    $halfStar = $rating - $fullStars >= 0.5 ? true : false;
    $emptyStars = 10 - $fullStars - ($halfStar ? 1 : 0);
@endphp

<div class="flex items-center">
    
    <span class="w-10 shadow-sm text-base text-center py-1 px-2 bg-slate-700/50 rounded-md text-slate-100 me-2">{{ $rating }}</span>

    @for ($i = 0; $i < $fullStars; $i++)
        <svg class="w-6 h-6 text-amber-400/80 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M10 15l-5.878 3.09 1.122-6.542L.49 6.91l6.564-.957L10 0l2.945 5.953 6.564.957-4.754 4.637 1.122 6.542z"/>
        </svg>
    @endfor

    @if ($halfStar)
        <svg class="w-6 h-6 text-amber-400/80 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <defs>
                <linearGradient id="half">
                    <stop offset="50%" stop-color="currentColor"/>
                    <stop offset="50%" stop-color="transparent"/>
                </linearGradient>
            </defs>
            <path d="M10 15l-5.878 3.09 1.122-6.542L.49 6.91l6.564-.957L10 0l2.945 5.953 6.564.957-4.754 4.637 1.122 6.542z" fill="url(#half)"/>
        </svg>
    @endif

    @for ($i = 0; $i < $emptyStars; $i++)
        <svg class="w-6 h-6 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M10 15l-5.878 3.09 1.122-6.542L.49 6.91l6.564-.957L10 0l2.945 5.953 6.564.957-4.754 4.637 1.122 6.542z"/>
        </svg>
    @endfor
</div>

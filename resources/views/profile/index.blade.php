<x-layout>
    <h3 class="text-lg font-semibold mb-1">{{ auth()->user()->name }}</h3>
    <div class="flex items-center gap-x-2 mb-10">
        <h3 class="text-sm font-medium text-teal-800">{{ $employee->department->name }}</h3>
        <h3 class="text-sm font-medium text-slate-400">{{ $employee->designation }}</h3>
        <section class="ms-auto flex gap-x-4">
            <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
                <x-carbon-request-quote class="h-5" />
                <a href="{{ route('profile.leave') }}" class="border-none bg-none underline">
                    Request Leave
                </a>
            </div>
            @if ($isVotingOpen)
                <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
                    <x-carbon-policy class="h-5" />
                    <a href="{{ route('employee-of-the-month.create') }}" class="border-none bg-none underline">
                        EOM Voting
                    </a>
                </div>
            @endif
        </section>
    </div>
    

    <h3 class="text-sm mb-2">Schedule</h3>
    <hr class="border-t border-slate-500/50 mb-4">
    
@if ($schedule)
    <div class="mb-4 flex text-sm items-center gap-x-2">
        <x-carbon-calendar-heat-map class="w-5 text-teal-700"/>
        <span class="font-medium rounded-sm text-teal-700/80">
            {{ $schedule->shift->name }}
        </span>
    </div>

    <table class="mt-4 mb-10">
        <thead>
            <tr class="bg-slate-300">
                @foreach ($weekdays as $day)
                    <th class="text-center">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $startTime = new DateTime($schedule->shift->start_time);
                $endTime = new DateTime($schedule->shift->end_time);
            @endphp

            <tr class="data-row">
                {{-- @foreach ($weekdays as $day)
                    <td class="text-center">
                        @if (in_array($day, $schedule->shift->weekdays) && !in_array($day, $schedule->dayoffs ?? []))
                            <span class="time-style bg-teal-700/70" style="margin-inline: 0">
                                {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                            </span>
                        @endif
                    </td>
                @endforeach --}}
                @foreach ($weekdays as $day)
                            <td class="text-center">
                                {{-- @if (is_null($selectedDay) || $selectedDay === $day)  --}}
                                    @if (in_array($day, $schedule->shift->weekdays) && !in_array($day, $schedule->dayoffs ?? []))
                                        @php

                                            $customTime = collect($schedule->customTimes)->firstWhere('day', $day);
                                                            
                                            if ($customTime) {
                                                // -- Use custom time if available
                                                $startTime = new DateTime($customTime['start_time']);
                                                $endTime = new DateTime($customTime['end_time']);
                                            } else {
                                                // -- Otherwise, use default shift time
                                                $startTime = new DateTime($schedule->shift->start_time);
                                                $endTime = new DateTime($schedule->shift->end_time);
                                            }

                                            $startHour = $startTime->format('H');
                                            $timePeriod = '';
                        
                                            if ($startHour >= 3 && $startHour < 11) {
                                                $timePeriod = 'MORNING';
                                            } elseif ($startHour >= 11 && $startHour < 15) {
                                                $timePeriod = 'NOON';
                                            } elseif ($startHour >= 15 && $startHour < 17) {
                                                $timePeriod = 'NIGHT';
                                            } else {
                                                $timePeriod = 'DAWN';
                                            }
                                        @endphp
                
                                        <p class="mb-1 text-slate-700/70">{{ $timePeriod }}</p>
                                        <span class="time-style bg-teal-700/70" style="margin-inline: 0">
                                            {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                                        </span>
                                    @endif
                                {{-- @endif --}}
                            </td>
                        @endforeach
            </tr>
        </tbody>
    </table>
@else
    <div class="mb-4 flex text-sm items-center gap-x-2">
        <x-carbon-calendar-heat-map class="w-5 text-teal-700"/>
        <span class="font-medium rounded-sm text-teal-700/80">
        No Schedule Yet
        </span>
    </div>
@endif
{{-- 
    <h3 class="text-sm mb-2">Performance</h3>
    <hr class="border-t border-slate-500/50 mb-4">

    <div class="mb-4 flex items-center text-sm gap-x-2">
        <x-carbon-calendar class="w-5 text-teal-700"/>
        <form method="GET" action="{{ route('profile.index') }}" class="text-teal-700/80 flex items-center gap-x-2 text-xs">
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
            <select name="month" id="month" class="w-36">
                <option value="">All</option>
                @foreach($months as $month)
                    <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                        {{ $month }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn">Filter</button>
        </form>
    </div>


    <table class="mt-4 mb-10">
        <thead>
            <tr class="bg-slate-300">
                <th class="px-4 py-2">
                    <form method="GET" action="{{ route('profile.index') }}">
                        <input type="hidden" name="month" value="{{ $selectedMonth }}">
                        <input type="hidden" name="sort_by" value="week">
                        <input type="hidden" name="sort_order" value="{{ $sortBy === 'week' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                        <div class="flex items-center gap-x-2">
                            <span>Week</span>
                            <button type="submit" class="flex flex-col me-2" style="font-size: 0.6rem;">
                                <span class="cursor-pointer {{ $sortBy === 'week' && $sortOrder === 'desc' ? 'text-cyan-600' : '' }}">▲</span>
                                <span class="cursor-pointer {{ $sortBy === 'week' && $sortOrder === 'asc' ? 'text-cyan-600' : '' }}">▼</span>
                            </button>
                        </div>
                    </form>
                </th>
                <th class="px-4 py-2">
                    <form method="GET" action="{{ route('profile.index') }}">
                        <input type="hidden" name="month" value="{{ $selectedMonth }}">
                        <input type="hidden" name="sort_by" value="rating">
                        <input type="hidden" name="sort_order" value="{{ $sortBy === 'rating' && $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                        <div class="flex items-center gap-x-2">
                            <span>Rating</span>
                            <button type="submit" class="flex flex-col me-2" style="font-size: 0.6rem;">
                                <span class="cursor-pointer {{ $sortBy === 'rating' && $sortOrder === 'desc' ? 'text-cyan-600' : '' }}">▲</span>
                                <span class="cursor-pointer {{ $sortBy === 'rating' && $sortOrder === 'asc' ? 'text-cyan-600' : '' }}">▼</span>
                            </button>
                        </div>
                    </form>
                </th>
                <th class="text-center">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employee->evaluations as $evaluation)
            @php
                $yearAndWeek = explode('-W', $evaluation->week); 
                $year = $yearAndWeek[0];
                $week = $yearAndWeek[1];
                $date = Carbon\Carbon::now()->setISODate($year, $week);
                $month = $date->format('F');  
                $weekOfMonth = ceil($date->day / 7); 
                $formattedWeek = $month . ' ' . $year . ' - Week ' . $weekOfMonth;
            @endphp
                <tr class="data-row" title="{{ $evaluation->week }}">
                    <td class="text-left max-w-[6rem]">
                        {{ $formattedWeek }}
                    </td>
                    <td class="max-w-[10rem]">
                        <x-rating-stars rating="{{ $evaluation->rating }}" />
                    </td>
                     <td class="max-w-sm pe-5">
                        <p style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                            {!! nl2br(e($evaluation->review)) !!}
                        </p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if ($employee->evaluations->count())
    <div class="text-xs mt-4">
        {{ $employee->evaluations->links()}}
    </div>
@endif --}}
</x-layout>
<x-layout>
    <div class="flex justify-between">
        <h3 class="text-lg font-semibold mb-3">Swap Schedule</h3>
        <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-user-profile class="h-5" />
            <a href="{{ route('profile.index') }}" class="border-none bg-none underline">
                Profile
            </a>
        </div>  
    </div>
    <section class="flex items-center gap-2 mb-4">
        <form method="GET" action="{{ route('profile.swap-request') }}" class="flex items-center gap-x-2">
            <input 
                type="week" 
                name="week" 
                class="w-52" 
                value="{{ $week ?? date('Y-\WW') }}" 
                onchange="this.form.submit()"
            >
        </form>
    </section>

    @php
        function getOrdinalSuffix($number) {
                $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
                if (($number % 100) >= 11 && ($number % 100) <= 13) {
                    return $number . 'th';
                }
                return $number . $ends[$number % 10];
            }
    @endphp

    @forelse ($schedules as $schedule)
        @php

            $weekString = $schedule->week;

            $date = new DateTime();
            $date->setISODate(substr($weekString, 0, 4), substr($weekString, 6));

            $month = $date->format('F'); 
            $year = $date->format('Y');

            $weekOfMonth = ceil($date->format('j') / 7); 

            $formattedWeek = getOrdinalSuffix($weekOfMonth);
        @endphp

            <div @class([
                'rounded-md p-4 mb-5 shadow-md',
                'bg-yellow-500/30' => $schedule->isRequestedByThisEmployee,
                'bg-slate-200/50' => !$schedule->isRequestedByThisEmployee,
            ]) >
                <div class="flex gap-x-2 items-end">
                    <img src="{{ asset('storage/' . $schedule->employee->picture) }}" alt="Employee Picture" class="w-16 h-16 object-cover rounded-md opacity-90 border border-teal-600">
                    <div class="w-56">
                        <h3 class="text-base font-semibold"> {{ $schedule->employee->firstname . ' ' . $schedule->employee->middlename . ' ' . $schedule->employee->lastname . ($schedule->employee->nameextension ? ' ' . $schedule->employee->nameextension : '') }}</h3>
                        <div class="flex gap-x-2">
                            <h3 class="text-xs font-medium text-pink-800/70">{{ $schedule->employee->designation }}</h3>
                        </div>
                    </div>
                </div>
            </section>

            <div class="mb-4 flex text-sm items-center gap-x-2">
                <x-carbon-calendar-heat-map class="w-5 text-teal-700"/>
                <div><span class="font-semibold">{{ $month }} {{ $year }}</span> <span class="font-medium text-slate-500">{{ $formattedWeek }} Week</span></div>
                <div class="font-bold">
                    •
                </div>
                <span class="font-medium rounded-sm text-teal-700/80">
                    {{ $schedule->shift->name }}
                </span>
            </div>
    
            <table class="mt-4 mb-5">
                <thead>
                    <tr class="bg-slate-300">
                        @foreach ($weekdays as $day)
                            @php
                                $date = new DateTime();
                                $date->setISODate(substr($schedule->week, 0, 4), substr($schedule->week, 6)); 
                                $date->modify("+" . (array_search($day, $weekdays)) . " days"); 
                                $actualDate = $date->format('Y-m-d'); // Get the actual date for this weekday
                            @endphp
                            <th class="text-center">
                                {{ $day }} <br>
                                <span class="text-[0.7rem] text-slate-400">{{ $actualDate }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $startTime = new DateTime($schedule->shift->start_time);
                        $endTime = new DateTime($schedule->shift->end_time);
                    @endphp
    
                    <tr class="data-row">
                        @foreach ($weekdays as $day)
                            <td class="text-center">
                                @if (in_array($day, $schedule->shift->weekdays))
                                @php
                                    $customTime = collect($schedule->customTimes)->firstWhere('day', $day);
                                    
                                    if ($customTime) {
                                        $startTime = new DateTime($customTime['start_time']);
                                        $endTime = new DateTime($customTime['end_time']);
                                    } else {
                                        $startTime = new DateTime($schedule->shift->start_time);
                                        $endTime = new DateTime($schedule->shift->end_time);
                                    }
                            
                                    $date = new DateTime();
                                    $date->setISODate(substr($schedule->week, 0, 4), substr($schedule->week, 6));
                                    $date->modify("+" . (array_search($day, $weekdays)) . " days"); 
                            
                                    $actualDate = $date->format('Y-m-d');
                                @endphp
                            
                                <p class="mb-1 text-slate-700/70">{{ $schedule->shift->name }}</p>
                                @if (in_array($day, $schedule->dayoffs ?? []) || in_array($actualDate, $employee->leaveRequestDates()->toArray()))
                                    <span class="time-style bg-neutral-500 px-5" style="margin-inline: 0">
                                        Dayoff
                                    </span>
                                @else
                                    <span class="time-style bg-teal-700/70" style="margin-inline: 0">
                                        {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                                    </span>
                                @endif
                            @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
    
            <form action="{{ route('profile.swap-post') }}" method="post" class="flex justify-end">
                @csrf
                <input type="hidden" name="week" value="{{ $week ?? date('Y-\WW') }}">
                <input type="hidden" name="employee" value="{{ $schedule->employee->id ?? '' }}">
               
                @if ($schedule->isRequestedByThisEmployee)
                    <div class="px-3 py-2 bg-yellow-800/80 text-white font-semibold tracking-wider rounded-sm">
                        SCHEDULE REQUESTED
                    </div>
                @else
                <div x-data="{ open: false }">
                    <button 
                        @click.prevent="open = true" 
                        type="button" 
                        class="btn w-56"
                        @disabled($iRequestedForThisWeek)
                    >
                        REQUEST SWAP
                    </button>
                
                    <div 
                        x-cloak 
                        x-show="open" 
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20"
                    >
                        <div class="bg-white pt-4 px-4 w-2/5 pb-3 rounded-lg">
                            <p class="mb-4 text-base font-medium">Are you sure you want to request a schedule swap?</p>
                            <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                <button 
                                    type="button" 
                                    @click="open = false" 
                                    class="btn"
                                >
                                    No
                                </button>
                                <form action="{{ route('profile.swap-post') }}" method="post" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="week" value="{{ $week ?? date('Y-\WW') }}">
                                    <input type="hidden" name="employee" value="{{ $schedule->employee->id ?? '' }}">
                                    <button type="submit" class="btn">
                                        Yes
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    @empty
    <div class="text-sm text-teal-800">
        
    </div>
    @endforelse
</x-layout>
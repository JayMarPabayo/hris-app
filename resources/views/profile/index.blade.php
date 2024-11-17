<x-layout>
    <div class="flex items-end gap-x-2 mb-10">
        <img src="{{ asset('storage/' . $employee->picture) }}" alt="Employee Picture" class="w-24 h-24 object-cover rounded-md opacity-90 border border-teal-600">
        <div class="flex flex-col">
            <h3 class="text-lg font-semibold">{{ auth()->user()->name }}</h3>
            <div class="flex gap-x-2">
                <h3 class="text-sm font-medium text-teal-800">{{ $employee->department->name }}</h3>
                <h3 class="text-sm font-medium text-pink-800/70">{{ $employee->designation }}</h3>
            </div>
        </div>
        <section class="ms-auto flex gap-x-4">
            <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
                <x-carbon-container-image-push-pull class="h-5" />
                <a href="{{ route('profile.swap-request') }}" class="border-none bg-none underline">
                    Request Schedule Swap
                </a>
            </div>
            @if ($employee->getRemainingCredits())
                <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
                    <x-carbon-request-quote class="h-5" />
                    <a href="{{ route('profile.leave') }}" class="border-none bg-none underline">
                        Request Leave
                    </a>
                </div>
            @endif
            @if ($isMonthlyEvaluationOpen)
                <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
                    <x-carbon-white-paper class="h-5" />
                    <a href="{{ route('profile.evaluation') }}" class="border-none bg-none underline">
                        Monthly Evaluation
                    </a>
                </div>
            @endif
        </section>
    </div>
    

    <h3 class="text-sm mb-2">Schedules</h3>
    <hr class="border-t border-slate-500/50 mb-4">


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

        <table class="mt-4 mb-10">
            <thead>
                <tr class="bg-slate-300">
                    @foreach ($weekdays as $day)
                        @php
                            $date = new DateTime();
                            $date->setISODate(substr($schedule->week, 0, 4), substr($schedule->week, 6)); 
                            $date->modify("+" . (array_search($day, $weekdays)) . " days"); 
                            $actualDate = $date->format('Y-m-d');
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
    @empty
        <div class="mb-4 flex text-sm items-center gap-x-2">
            <x-carbon-calendar-heat-map class="w-5 text-teal-700"/>
            <span class="font-medium rounded-sm text-teal-700/80">
                No Schedule Yet
            </span>
        </div>
    @endforelse
    
</x-layout>
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
            <select name="employee_id" class="w-80" >
                <option value="" hidden disabled selected>Select Employee</option>
                @foreach ($employees as $employee)
                    <option value="{{  $employee->id }}" @selected($employee_id == $employee->id)>{{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}</option>
                @endforeach
            </select>
            <input type="week" name="week" class="w-52" value="{{ $week ?? date('Y-\WW') }}">
            <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">Check</button>
        </form>
    </section>

    @if ($schedule)
        @php
            function getOrdinalSuffix($number) {
                $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
                if (($number % 100) >= 11 && ($number % 100) <= 13) {
                    return $number . 'th';
                }
                return $number . $ends[$number % 10];
            }

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
            <input type="hidden" name="employee" value="{{ $employee_id ?? '' }}">
            <button type="submit" class="btn w-56">REQUEST SWAP</button>
        </form>
    @else
        <div class="text-sm text-teal-800"></div>
    @endif

</x-layout>
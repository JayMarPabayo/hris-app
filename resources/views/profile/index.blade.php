<x-layout>
    <div class="flex items-end gap-x-2 mb-10 relative">
        <img src="{{ asset('storage/' . $employee->picture) }}" alt="Employee Picture" class="w-24 h-24 object-cover rounded-md opacity-90 border border-teal-600">
        <div class="flex flex-col">
            <h3 class="text-lg font-semibold text-white">{{ auth()->user()->name }}</h3>
            <div class="flex gap-x-2">
                <h3 class="text-sm font-medium text-teal-500">{{ $employee->department->name }}</h3>
                <h3 class="text-sm font-medium text-slate-400">{{ $employee->designation }}</h3>
            </div>
        </div>
        <section class="ms-auto flex gap-x-4">
            <div class="flex gap-x-2 items-center text-white hover:text-teal-600 hover:scale-105 active:scale-95 duration-300">
                <x-carbon-container-image-push-pull class="h-5" />
                <a href="{{ route('profile.swap-request') }}" class="border-none bg-none underline">
                    Request Schedule Swap
                </a>
            </div>
            <div class="flex gap-x-2 items-center text-white hover:text-teal-600 hover:scale-105 active:scale-95 duration-300">
                <x-carbon-request-quote class="h-5" />
                <a href="{{ route('profile.leave') }}" class="border-none bg-none underline">
                    Request Leave
                </a>
            </div>
            @if ($isMonthlyEvaluationOpen && !$hasVotedForThisMonth)
                <div class="flex gap-x-2 items-center text-white hover:text-teal-600 hover:scale-105 active:scale-95 duration-300">
                    <x-carbon-white-paper class="h-5" />
                    <a href="{{ route('profile.evaluation') }}" class="border-none bg-none underline">
                        Monthly Evaluation
                    </a>
                </div>
            @endif
        </section>

        @if ($notification)
            <div class="absolute top-1 end-1 flex justify-between items-center gap-x-2 rounded-md p-2 bg-yellow-500 hover:bg-yellow-500/50 shadow-md duration-300">
                <x-carbon-information-filled class="h-5"/>
                <div class="font-medium">{{ $notification }}</div>
                <form action="{{ route('notification.delete', $employee->user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">
                        <x-carbon-close-large class="h-5 ms-5 hover:scale-110 active:scale-100" />
                    </button>
                </form>
            </div>
        @endif

        @if ($hasConsentRequest)
            <div class="absolute top-1 end-1 flex justify-between items-center gap-x-4 rounded-md p-2 bg-white hover:bg-white/90 shadow-md duration-300">
                <x-ionicon-swap-horizontal-outline class="h-10 text-teal-600"/>
                <div>
                    <p>Schedule Swap Consent</p>
                    <p>Requested by: <span class="font-semibold">{{ "{$hasConsentRequest->employee->lastname}, {$hasConsentRequest->employee->firstname} " . strtoupper(substr($hasConsentRequest->employee->middlename, 0, 1)) . "." }}</span></p>
                    
                </div>
                <div x-data="{ open: false }">
                    <button @click.prevent="open = true" title="Delete" class="btn-delete w-32">
                        Reject
                    </button>
                    <div x-cloak x-show="open" class="fixed inset-0 flex bg-black backdrop-blur-sm bg-opacity-50">
                        <div class="bg-white pt-4 px-4 pb-3 rounded-lg h-fit mx-auto mt-[10%]">
                            <div class="flex items-center gap-x-2 mb-5 text-slate-600">
                                <x-ionicon-thumbs-down-sharp class="h-5 fill-rose-500" />
                                <p>Are you sure you want to reject this request?</p>
                            </div>
                            <div class="flex justify-end gap-2 pt-3 border-t border-slate-300 ps-10">
                                <button type="button" @click="open = false" class="btn w-32">No</button>
                                <form
                                id="reject-consent-form"
                                action="{{ route('swap.consent.delete', $hasConsentRequest->id) }}"
                                method="POST"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                    type="submit"
                                    class="btn-delete w-32"
                                    x-on:click="submitting=true; document.getElementById('reject-consent-form').submit();"
                                    >Yes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-data="{ open: false }">
                    <button @click.prevent="open = true" title="Delete" class="btn-submit w-32">
                        Approve
                    </button>
                    <div x-cloak x-show="open" class="fixed inset-0 flex bg-black backdrop-blur-sm bg-opacity-50">
                        <div class="bg-white pt-4 px-4 pb-3 rounded-lg h-fit mx-auto mt-[10%]">
                            <div class="flex items-center gap-x-2 mb-5 text-slate-600">
                                <x-ionicon-thumbs-up-sharp class="h-5 fill-teal-600" />
                                <p>Approval Confirmation</p>
                            </div>
                            <div class="flex justify-end gap-2 pt-3 border-t border-slate-300 ps-10">
                                <button type="button" @click="open = false" class="btn w-32">No</button>
                                <form
                                id="approve-consent-form"
                                action="{{ route('swap.consent.approve', $hasConsentRequest->id) }}"
                                method="POST"
                                >
                                    @csrf
                                    @method('PUT')
                                    <button
                                    type="submit"
                                    class="btn-submit w-32"
                                    x-on:click="submitting=true; document.getElementById('approve-consent-form').submit();"
                                    >Yes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    

    <h3 class="text-sm mb-2 text-white">Schedules</h3>
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
            <div>
                <span class="font-semibold text-white">{{ $month }} {{ $year }}</span>
                <span class="font-medium text-white ms-1">{{ $formattedWeek }} Week</span>
            </div>
            <div class="font-bold">
                •
            </div>
            <span class="font-medium rounded-sm text-teal-600/80">
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
                            $actualDate = $date->format('M d, Y');
                        @endphp
                        <th class="text-center">
                            {{ $day }} <br>
                            <span class="text-[0.7rem] text-slate-500">{{ $actualDate }}</span>
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
                        @php
                            $date = new DateTime();
                            $date->setISODate(substr($schedule->week, 0, 4), substr($schedule->week, 6)); 
                            $date->modify("+" . (array_search($day, $weekdays)) . " days"); 
                            $actualDate = $date->format('Y-m-d');
                        @endphp
                        @if (in_array($actualDate, $employee->leaveRequestDates()->toArray()))
                            <span class="time-style bg-neutral-500 px-5" style="margin-inline: 0">
                                Leave
                            </span>
                        @else
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
                                @endphp
                            
                                <p class="mb-1 text-white/80">{{ $schedule->shift->name }}</p>
                                @if (in_array($day, $schedule->dayoffs ?? []))
                                    <span class="time-style bg-neutral-500 px-5" style="margin-inline: 0">
                                        Dayoff
                                    </span>
                                @else
                                    <span class="time-style bg-teal-700/70" style="margin-inline: 0">
                                        {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                                    </span>
                                @endif
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
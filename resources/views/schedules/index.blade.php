<x-layout>
    <h3 class="text-base font-semibold mb-3">Schedules</h3>

    <form method="GET" action="{{ route('schedules.index') }}" class="flex items-center gap-2 mb-4">
        {{-- <input type="search" placeholder="Search..." name="search" value="{{ request('search') }}" class="flex-grow">
        <select name="shift" class="w-60">
            <option value="" selected>All Shifts</option>
            @foreach ($shifts as $shift)
                <option value="{{ $shift->id }}" @selected(request('shift') == $shift->id)>
                    {{ $shift->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">
            Search<span class="text-lg leading-3">⌕</span>
        </button> --}}
        <a href="{{ route('schedules.create') }}" class="btn w-32">Add New ✚</a>
        <form method="GET" action="{{ route('schedules.index') }}">
            <select name="day" class="w-60" onchange="this.form.submit()">
                <option value="" selected>All</option>
                @foreach ($weekdays as $week)
                    <option value="{{ $week }}" @selected($selectedDay === $week)>{{ $week }}</option>
                @endforeach
            </select>
        </form>
    </form>
    @foreach ($schedules as $department => $departmentSchedules)
        <h1 class="text-2xl font-bold mt-8 mb-4">{{ $department }}</h1>
        <table class="w-full mb-8">
            <thead>
                <tr class="bg-slate-300">
                    <th>Employee</th>
                    @foreach ($weekdays as $day)

                    <th class="text-center">
                        <a href="{{ route('schedules.index', array_merge(request()->query(), ['day' => $day])) }}" class="hover:tracking-wider active:tracking-tighter duration-300
                            {{-- {{ $selectedDay == $day ? 'text-sky-800 tracking-wider' : '' }} --}}
                            ">
                            {{ $day }}
                        </a>
                    </th>
                    @endforeach
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                $colors = [
                    'bg-rose-600/60',
                    'bg-blue-600/60',
                    'bg-amber-600/60',
                    'bg-teal-600/60',
                    'bg-stone-600/60',
                    'bg-cyan-600/60',
                    'bg-zinc-600/60',
                    'bg-pink-600/60',
                    'bg-purple-600/60',
                    'bg-yellow-600/60',
                    'bg-green-600/60',
                    'bg-red-600/60',
                    'bg-gray-600/60',
                    'bg-indigo-600/60',
                    'bg-lime-600/60',
                    'bg-fuchsia-600/60',
                    'bg-violet-600/60',
                    'bg-sky-600/60',
                    'bg-orange-600/60',
                    'bg-emerald-600/60',
                    'bg-rose-500/60',
                    'bg-blue-500/60',
                    'bg-amber-500/60',
                    'bg-teal-500/60',
                    'bg-stone-500/60',
                    'bg-cyan-500/60',
                    'bg-zinc-500/60',
                    'bg-pink-500/60',
                    'bg-purple-500/60',
                    'bg-yellow-500/60',
                    'bg-green-500/60',
                    'bg-red-500/60',
                    'bg-gray-500/60',
                    'bg-indigo-500/60',
                    'bg-lime-500/60',
                    'bg-fuchsia-500/60',
                    'bg-violet-500/60',
                    'bg-sky-500/60',
                    'bg-orange-500/60',
                    'bg-emerald-500/60'
                ];
                @endphp
                @forelse ($departmentSchedules as $index => $schedule)
                    @php
                        $startTime = new DateTime($schedule->shift->start_time);
                        $endTime = new DateTime($schedule->shift->end_time);
                        $colorClass = $colors[($schedule->shift->id - 1) % count($colors)];
                    @endphp
                    <tr class="data-row" title="{{ $schedule->shift->name }}">
                        <td class="max-w-36">
                            <div class="text-sm font-medium">{{ "{$schedule->employee->lastname}, {$schedule->employee->firstname} " . strtoupper(substr($schedule->employee->middlename, 0, 1)) . "." }}</div>
                            <span class="block text-teal-700">
                                {{ $schedule->employee->department->name }}
                            </span>
                            <span class="block text-slate-500/70 truncate">
                                {{ $schedule->employee->designation }}
                            </span>
                        </td>
                        @foreach ($weekdays as $day)
                            <td class="text-center">
                                {{-- Show time in/out only if selectedDay is null or matches the current day --}}
                                @if (is_null($selectedDay) || $selectedDay === $day)
                                    @if (in_array($day, $schedule->shift->weekdays) && !in_array($day, $schedule->dayoffs ?? []))
                                        @php
                                            $customTime = collect($schedule->customTimes)->firstWhere('day', $day);
                                            $startTime = $customTime ? new DateTime($customTime['start_time']) : new DateTime($schedule->shift->start_time);
                                            $endTime = $customTime ? new DateTime($customTime['end_time']) : new DateTime($schedule->shift->end_time);

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
                                        <span class="time-style {{ $colorClass }}" style="margin-inline: 0">
                                            {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                                        </span>
                                    @endif
                                @endif
                            </td>
                        @endforeach

                        <td class="flex justify-center items-center gap-x-1 px-2" style="margin-top: 0.5rem;">
                            {{-- EDIT BUTTON --}}
                            <div x-data="{ openEdit: false }">
                                <button type="button" @click.prevent="openEdit = true" title="Delete" class="btn-add" style="padding: 0.3rem 0.8rem;">
                                    <x-carbon-pen class="w-4 mx-auto"/>
                                </button>
                                <div x-cloak x-show="openEdit" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                    <div class="bg-white pt-4 px-6 pb-3 rounded-lg">
                                        <form
                                        id="update-schedule-form-{{ $schedule->id }}"
                                        action="{{ route('schedules.update', $schedule) }}"
                                        method="POST"
                                        >
                                            @csrf
                                            @method("PUT")
                                            <h5 class="text-sm mb-4">Update Schedule</h5>

                                            <div class="px-2 py-1 rounded-sm bg-stone-600/20 font-medium">
                                                <span class="block">
                                                    {{ "{$schedule->employee->lastname}, {$schedule->employee->firstname} " . strtoupper(substr($schedule->employee->middlename, 0, 1)) . "." }}
                                                </span>
                                                <span class="text-teal-700">
                                                    {{ $schedule->employee->department->name }}
                                                </span>
                                                /
                                                <span class="text-slate-500/70">
                                                    {{ $schedule->employee->designation }}
                                                </span>
                                            </div>

                                            <div class="flex gap-x-5" x-data="{ showCustomizeTime: true }">
                                                <div :class="showCustomizeTime ? 'min-w-80' : 'w-full'" class="mb-2">
                                                    <input type="hidden" name="employee_id" value="{{ $schedule->employee_id }}">
                                                    
                                                    @livewire('select-shifts', ['shiftId' => $schedule->shift_id])
                    
                                                    {{-- Add Dayoffs field --}}
                                                    <h5 class="text-xs mt-4">Day Offs</h5>
                                                    <div class="border border-slate-200 p-2 grid grid-cols-2 gap-2">
                                                        @foreach($weekdays as $day)
                                                            <label class="flex justify-between items-center py-1 px-3 bg-slate-200 rounded-md shadow-sm cursor-pointer">
                                                                <span class="text-xs font-medium">{{ $day }}</span>
                                                                <input 
                                                                    type="checkbox" 
                                                                    name="dayoffs[]" 
                                                                    value="{{ $day }}"
                                                                    class="w-fit focus:outline-none cursor-pointer"
                                                                    {{ is_array($schedule->dayoffs) && in_array($day, $schedule->dayoffs) ? 'checked' : '' }}
                                                                />
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            
                                                <div x-show="showCustomizeTime" class="flex flex-col gap-y-2 justify-between py-2 px-4 rounded-md bg-emerald-500/20  mt-4 mb-2 ">
                                                    <h5 class="text-xs">Customize Time</h5>
                                                    @foreach($weekdays as $day)
                                                        @if (in_array($day, $schedule->shift->weekdays) && !in_array($day, $schedule->dayoffs ?? []))
                                                            @php
                                                                // -- Find custom time for the current day
                                                                $customTime = collect($schedule->customTimes)->firstWhere('day', $day);
                                                                
                                                                // -- Use custom time if available, otherwise use shift time
                                                                $startTimeValue = $customTime ? $customTime['start_time'] : $schedule->shift->start_time;
                                                                $endTimeValue = $customTime ? $customTime['end_time'] : $schedule->shift->end_time;
                                                            @endphp
                                                    
                                                            <div class="flex items-center justify-between gap-x-3">
                                                                <div class="font-semibold w-60 text-slate-600/70">{{ $day }}</div>
                                                                <input type="hidden" name="day[]" value="{{ $day }}" />
                                                                <input type="hidden" name="schedule_id[]" value="{{ $schedule->id }}" />
                                                    
                                                                <input 
                                                                    type="time" 
                                                                    name="start_time[]"
                                                                    value="{{ $startTimeValue }}" 
                                                                />
                                                                <input 
                                                                    type="time" 
                                                                    name="end_time[]"
                                                                    value="{{ $endTimeValue }}" 
                                                                />
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="flex justify-end gap-x-4 pt-3 border-t border-slate-200">
                                                <button type="button" @click="openEdit = false" class="btn w-52 shadow-md">
                                                    Cancel
                                                </button>
                                                <button
                                                type="submit"
                                                class="btn w-52 shadow-md"
                                                x-on:click="submitting=true; document.getElementById('update-schedule-form-{{ $schedule->id }}').submit();"
                                                >
                                                    Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- DELETE BUTTON --}}
                            <div x-data="{ openDelete: false }">
                                <button type="button" @click.prevent="openDelete = true" title="Delete" class="btn-add" style="padding: 0.3rem 0.8rem;">
                                    <x-carbon-trash-can class="w-4 mx-auto"/>
                                </button>
                                <div x-cloak x-show="openDelete" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                    <div class="bg-white pt-4 px-6 pb-3 rounded-lg">
                                        <p class="text-sm">Are you sure you want to delete this Schedule?</p>
                                        <div class="flex justify-end gap-2 mt-3 pt-3 border-t border-slate-200">
                                            <button @click="openDelete = false" class="btn">No</button>
                                            <form
                                            id="delete-schedule-form-{{ $schedule->id }}"
                                            action="{{ route('schedules.destroy', $schedule) }}"
                                            method="POST"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                type="submit"
                                                class="btn"
                                                x-on:click="submitting=true; document.getElementById('delete-schedule-form-{{ $schedule->id }}').submit();"
                                                >Yes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="py-20">
                            <x-empty-alert />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach
    
    
    {{-- @if ($schedules->count())
        <div class="text-xs mt-4">
            {{ $schedules->links()}}
        </div>
    @endif --}}
    
</x-layout>

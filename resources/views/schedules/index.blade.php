<x-layout>
    <h3 class="text-base font-semibold mb-3">Schedules</h3>

    <form method="GET" action="{{ route('schedules.index') }}" class="flex items-center gap-2 mb-4">
        <input type="search" placeholder="Search..." name="search" value="{{ request('search') }}" class="flex-grow">
        <select name="shift" class="w-60">
            <option value="" selected>All Shifts</option>
            @foreach ($shifts as $shift)
                <option value="{{ $shift->id }}" @selected(request('shift') == $shift->id)>
                    {{ $shift->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">Search<span class="text-lg leading-3">⌕</span></button>
        <a href="{{ route('schedules.create') }}" class="btn w-32">Add New ✚</a>
    </form>

    <table>
        <thead>
            <tr class="bg-slate-300">
                <th>Employee</th>
                @foreach ($weekdays as $day)
                    <th class="text-center">{{ $day }}</th>
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
            @forelse ($schedules as $index => $schedule)
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
                            @if (in_array($day, $schedule->shift->weekdays) && !in_array($day, $schedule->dayoffs ?? []))
                                <span class="time-style {{ $colorClass }}" style="margin-inline: 0">
                                    {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                                </span>
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
                                <div class="min-w-96 max-w-fit bg-white pt-4 px-6 pb-3 rounded-lg">
                                    <form
                                    id="update-schedule-form-{{ $index }}"
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

                                        <input type="hidden" name="employee_id" value="{{ $schedule->employee_id }}">
                                        
                                        @livewire('select-shifts', ['shiftId' => $schedule->shift_id])
        
                                        {{-- Add Dayoffs field --}}
                                        <div class="mt-4">
                                            <label for="dayoffs">Day Offs</label>
                                            <select name="dayoffs[]" id="dayoffs" class="form-multiselect block w-full" multiple>
                                                <option value="Monday" {{ in_array('Monday', $schedule->dayoffs ?? []) ? 'selected' : '' }}>Monday</option>
                                                <option value="Tuesday" {{ in_array('Tuesday', $schedule->dayoffs ?? []) ? 'selected' : '' }}>Tuesday</option>
                                                <option value="Wednesday" {{ in_array('Wednesday', $schedule->dayoffs ?? []) ? 'selected' : '' }}>Wednesday</option>
                                                <option value="Thursday" {{ in_array('Thursday', $schedule->dayoffs ?? []) ? 'selected' : '' }}>Thursday</option>
                                                <option value="Friday" {{ in_array('Friday', $schedule->dayoffs ?? []) ? 'selected' : '' }}>Friday</option>
                                                <option value="Saturday" {{ in_array('Saturday', $schedule->dayoffs ?? []) ? 'selected' : '' }}>Saturday</option>
                                                <option value="Sunday" {{ in_array('Sunday', $schedule->dayoffs ?? []) ? 'selected' : '' }}>Sunday</option>
                                            </select>
                                        </div>

                                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                            <button type="button" @click="openEdit = false" class="btn">
                                                Cancel
                                            </button>
                                            <button
                                            type="submit"
                                            class="btn"
                                            x-on:click="submitting=true; document.getElementById('update-schedule-form-{{ $index }}').submit();"
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
                                        id="delete-schedule-form-{{ $index }}"
                                        action="{{ route('schedules.destroy', $schedule) }}"
                                        method="POST"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                            type="submit"
                                            class="btn"
                                            x-on:click="submitting=true; document.getElementById('delete-schedule-form-{{ $index }}').submit();"
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
    @if ($schedules->count())
        <div class="text-xs mt-4">
            {{ $schedules->links()}}
        </div>
    @endif
    
</x-layout>

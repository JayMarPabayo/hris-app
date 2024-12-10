<x-layout>
    <div class="flex gap-x-2 justify-between items-center mb-5">
        <h3 class="text-base font-semibold text-white">Add Schedules</h3>
        <div class="flex gap-x-2 items-center text-white hover:text-teal-500 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-event-schedule class="h-5" />
            <a href="{{ route('schedules.index') }}" class="border-none bg-none underline">
                Schedules
            </a>
        </div>
    </div>
    <form method="GET" action="{{ route('schedules.create') }}" class="flex items-center gap-2 mb-4" x-data>
        <input 
            type="search" 
            placeholder="Search..." 
            name="search" 
            value="{{ request('search') }}" 
            class="flex-grow"
            @input.debounce.500ms="$event.target.form.submit()" 
        >
        <input 
            type="week" 
            name="week" 
            class="w-48 block mt-1" 
            value="{{ request('week') ?? date('Y-\WW') }}"
            min="{{ date('Y-\WW') }}" 
            @change="$event.target.form.submit()"
        >
    </form>
    <table>
        <thead>
            <tr class="bg-slate-300">
                <th>ID</th>
                <th>Employee</th>
                <th>Department/Designation</th>
                <th>Shift</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $index => $employee)
                <tr class="data-row">
                    <td>
                        {{ $employee->id }}
                    </td>
                    <td>
                        {{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}
                    </td>
                    <td>
                        <span class="block text-teal-500">
                            {{ $employee->department->name }}
                        </span>
                        <span class="block text-slate-200">
                            {{ $employee->designation }}
                        </span>
                    </td>
                    <td>
                        <div x-data="{ openAddSchedule: false }">
                            <button @click.prevent="openAddSchedule = true" class="btn">
                                ✚
                            </button>
                            <div x-cloak x-show="openAddSchedule" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                <div class="w-[30rem] bg-white pt-4 px-4 pb-3 rounded-lg text-slate-600">
                                    <form
                                    id="add-schedule-form-{{ $index }}"
                                    action="{{ route('schedules.store') }}"
                                    method="POST"
                                    >
                                        @csrf
                                        <div class="flex items-center gap-x-2 mb-5 text-slate-600">
                                            <x-ionicon-create class="h-5 fill-teal-500" />
                                            <p>Create Schedule</p>
                                        </div>

                                        <div class="px-2 py-1 rounded-sm bg-stone-600/20 font-medium mb-3">
                                            <span class="block">
                                                {{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}
                                            </span>
                                            <span class="text-teal-700">
                                                {{ $employee->department->name }}
                                            </span>
                                            /
                                            <span class="text-slate-500/70">
                                                {{ $employee->designation }}
                                            </span>
                                        </div>

                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                        
                                        <label for="week" class="text-slate-500">Week</label>
                                        <input type="week" name="week" class="w-full block mt-1" min="{{ date('Y-\WW') }}" value="{{ request('week') ?? date('Y-\WW') }}" readonly>

                                        @livewire('select-shifts')

                                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                            <button type="button" @click="openAddSchedule = false" class="btn w-32">
                                                Cancel
                                            </button>
                                            <button
                                            type="submit"
                                            class="btn-submit w-32"
                                            x-on:click="submitting=true; document.getElementById('add-schedule-form-{{ $index }}').submit();"
                                            >
                                                Submit
                                            </button>
                                        </div>
                                    </form>
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
    <i class="text-xs text-white italic mt-2">
        List of employees without schedule in this week.
    </i>
    @if ($employees->count())
        <div class="text-xs mt-2">
            {{ $employees->links()}}
        </div>
    @endif

</x-layout>


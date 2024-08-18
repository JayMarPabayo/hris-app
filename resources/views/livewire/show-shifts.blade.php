<div>
    <div class="flex items-center gap-x-2 w-full mb-3">
        <select class="w-60" wire:model="selectedShift">
            <option value="" hidden disabled selected>Select Shift</option>
            <option value="0">All Shift</option>
            @foreach ($shifts as $shift)
                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
            @endforeach
        </select>

        <button type="button" class="btn" wire:loading.attr="disabled" wire:click.prevent="getEmployeesByShift">
            Search
        </button>
        <div wire:loading.delay wire:target="getEmployeesByShift">
            <x-carbon-awake class="w-5 text-slate-500 animate-spin" />
        </div>
        
        @if ($schedules)
            <a
            href="{{ route('reports.schedules', $this->getShift() ) }}"
            class="btn ml-auto"
            target="_blank"
            >
                <x-carbon-printer class="w-4" />
            </a>
        @endif

    </div>
    <table>
        <thead>
            <tr class="bg-slate-300">
                <th>ID</th>
                <th class="flex items-center justify-between">
                    <span>
                        Name
                    </span>
                    <button type="submit" wire:click.prevent="setSort" class="flex flex-col me-2" style="font-size: 0.6rem;">
                        <span class="cursor-pointer {{ $sort === 'desc' ? 'text-cyan-600' : '' }}">▲</span>
                        <span class="cursor-pointer {{ $sort === 'asc' ? 'text-cyan-600' : '' }}">▼</span>
                    </button>
                </th>
                <th>Schedule</th>
                <th class="text-center">Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($schedules as $schedule)

                @php
                    $startTime = new DateTime($schedule->shift->start_time);
                    $endTime = new DateTime($schedule->shift->end_time);
                @endphp

                <tr class="data-row">
                    <td>{{ $schedule->employee->id }}</td>
                    <td>{{ "{$schedule->employee->lastname}, {$schedule->employee->firstname} " . strtoupper(substr($schedule->employee->middlename, 0, 1)) . "." }}</td>
                    <td class="flex gap-x-2 justify-start items-center">
                        @foreach ($schedule->shift->weekdays as $day)
                            <div class="time-style bg-white" style="margin-inline: 0; color: darkgreen">
                                {{ strtoupper(substr($day, 0, 3)) }}
                            </div>
                        @endforeach
                    </td>
                    <td class="text-center">
                        <span class="time-style" style="margin-inline: 0; color: darkgreen">
                            {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                        </span>
                    </td>
                    
                </tr>
            @empty
                <tr>
                   <td colspan="100%" class="py-20"> <x-empty-alert /></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
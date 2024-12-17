<div>
    <div class="flex items-center gap-x-2 w-full mb-3">
        <select class="w-60" wire:model="selectedShift" wire:change="getEmployeesByShift">
            <option value="" hidden disabled selected>Select Shift</option>
            <option value="0">All Shift</option>
            @foreach ($shifts as $shift)
                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
            @endforeach
        </select>

        <input type="week" class="w-52" wire:model="week" wire:change="getEmployeesByShift" />

        <div wire:loading.delay wire:target="getEmployeesByShift">
            <x-ionicon-logo-ionic class="w-5 text-teal-300 animate-spin" />
        </div>
        
        @if ($schedules)
            <a
            href="{{ route('reports.schedules', ['shift' => $this->getShift(), 'week' => $this->week]) }}"
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
                <th class="flex items-center justify-start gap-x-4">
                    <span>
                        Name
                    </span>
                    <button type="submit" wire:click.prevent="setSort" class="flex flex-col me-2" style="font-size: 0.6rem;">
                        <span class="cursor-pointer {{ $sort === 'desc' ? 'text-cyan-600' : '' }}">▲</span>
                        <span class="cursor-pointer {{ $sort === 'asc' ? 'text-cyan-600' : '' }}">▼</span>
                    </button>
                </th>
                <th>Month</th>
                <th>Week</th>
                <th>Weekdays</th>
                <th>Shift</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($schedules as $schedule)

                @php
                    $startTime = new DateTime($schedule->shift->start_time);
                    $endTime = new DateTime($schedule->shift->end_time);
                @endphp

                <tr class="data-row" style="padding-block: 0.5rem">
                    <td>{{ $schedule->employee->id }}</td>
                    <td>{{ "{$schedule->employee->lastname}, {$schedule->employee->firstname} " . strtoupper(substr($schedule->employee->middlename, 0, 1)) . "." }}</td>
                    <td>{{ $schedule->week }}</td>
                    <td>{{ $schedule->week }}</td>
                    <td>
                        <div class="flex gap-x-2 justify-start items-center">
                            @foreach ($schedule->shift->weekdays as $day)
                                @if (!in_array($day, $schedule->dayoffs ?? []))
                                    <div class="time-style bg-white" style="margin-inline: 0; color: darkgreen">
                                        {{ strtoupper(substr($day, 0, 3)) }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <div class="text-sm font-medium">
                            {{ $schedule->shift->name }}
                        </div>
                        <div class="time-style bg-teal-700/80" style="margin: 0">
                            {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                        </div> 
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
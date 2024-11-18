<div>
    <div class="flex items-center gap-x-2 w-full mb-3">
        <select class="w-60" wire:model="selectedShift" wire:change="getEmployeesByShift">
            <option value="" hidden disabled selected>Select Shift</option>
            <option value="0">All Shift</option>
            @foreach ($shifts as $shift)
                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
            @endforeach
        </select>

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
                       <div class="flex flex-col">
                            <p class="text-base font-medium">
                                {{ $schedule->shift->name }}
                            </p>
                            <p class="time-style" style="margin-inline: 0; color: darkgreen; padding-left: 0">
                                {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                            </p>
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
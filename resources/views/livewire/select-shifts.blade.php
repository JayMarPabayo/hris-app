<div class="my-4">
    <label for="shift_id">Select Shift</label>
    <div class="flex items-center gap-x-2">
        <select 
            wire:model.live="selectedShift" 
            name="shift_id" 
            class="w-full cursor-pointer"
            @change="showCustomizeTime = false"
        >
            <option value="" hidden selected>Select Shift</option>
            @foreach ($shifts as $item)
                <option value="{{ $item->id }}">
                    {{ $item->name }}
                </option>
            @endforeach
        </select>
        <div wire:loading.delay>
            <x-carbon-awake class="w-5 text-slate-500 animate-spin" />
        </div>
    </div>

    @if ($shift)
        <div wire:loading.remove class="mt-3 rounded-sm p-2 bg-slate-300/30">
            @php
                $startTime = new DateTime($shift->start_time);
                $endTime = new DateTime($shift->end_time);
            @endphp
            <div class="text-emerald-600 font-medium mb-2">
                {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
            </div>
            <div class="flex gap-x-2 justify-start items-center">
                @foreach ($shift->weekdays as $day)
                    <div class="time-style bg-emerald-600/50" style="margin-inline: 0">
                        {{ strtoupper(substr($day, 0, 3)) }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

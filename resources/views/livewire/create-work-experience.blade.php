<div>
    <div class="flex items-center gap-x-2 mb-3">
        <h3 class="text-sm font-normal text-teal-600">Work Experience</h3>
        <button type="button" class="btn-add" wire:click.prevent="addWorkExperience">✚</button>
    </div>
    <div class="flex flex-col gap-y-2 mb-2 pb-5 border-b border-slate-300">
        @foreach ($workexperiences as $index => $workexperience)
            <div class="grid grid-cols-12 gap-y-1 gap-x-2 py-4 px-3 border-b border-slate-300 bg-rose-600/10 shadow-sm rounded-md">
                
                <div class="col-span-4">
                    <label for="workexperiences[{{ $index }}][company]">Department/Agency/Office/Company</label>
                    <input
                    type="text"
                    placeholder="Write in full/Do not abbreviate"
                    name="workexperiences[{{ $index }}][company]"
                    value="{{ old("workexperiences.{$index}.company", $workexperience['company']) }}"
                    class="{{ $errors->has("workexperiences.{$index}.company") ? 'border-red-400' : '' }}" />
                </div>  

                <div class="col-span-3">
                    <label for="workexperiences[{{ $index }}][position]">Position</label>
                    <input
                    type="text"
                    placeholder="Job Title"
                    name="workexperiences[{{ $index }}][position]"
                    value="{{ old("workexperiences.{$index}.position", $workexperience['position']) }}"
                    class="{{ $errors->has("workexperiences.{$index}.position") ? 'border-red-400' : '' }}" />
                </div>

                <div class="col-span-2">
                    <label for="workexperiences[{{ $index }}][start]">Start</label>
                    <input
                    type="date"
                    placeholder="Year"
                    name="workexperiences[{{ $index }}][start]"
                    title="Start"
                    value="{{ old("workexperiences.{$index}.start", $workexperience['start']) }}"
                    class="{{ $errors->has("workexperiences.{$index}.start") ? 'border-red-400' : '' }}" />
                </div>

                <div class="col-span-2">
                    <label for="workexperiences[{{ $index }}][end]">End</label>
                    <input
                    type="date"
                    placeholder="Year"
                    name="workexperiences[{{ $index }}][end]"
                    title="End"
                    value="{{ old("workexperiences.{$index}.end", $workexperience['end']) }}"
                    class="{{ $errors->has("workexperiences.{$index}.end") ? 'border-red-400' : '' }}" />
                </div>

                <div class="row-span-2 w-fit ml-auto flex justify-end items-center">
                    <button type="button" title="Remove" class="btn" wire:click.prevent="removeWorkExperience({{ $index }})">
                        <x-carbon-trash-can class="w-4 mx-auto"/>
                    </button>
                </div>

                <div class="col-span-2">
                    <label for="workexperiences[{{ $index }}][monthlysalary]">Monthly Salary</label>
                    <input
                    type="number"
                    name="workexperiences[{{ $index }}][monthlysalary]"
                    step=".01"
                    placeholder="₱"
                    value="{{ old("workexperiences.{$index}.monthlysalary", $workexperience['monthlysalary']) }}"
                    class="{{ $errors->has("workexperiences.{$index}.monthlysalary") ? 'border-red-400' : '' }}" />
                </div>

                <div class="col-span-3">
                    <label for="workexperiences[{{ $index }}][paygrade]">Salary/Job/Pay Grade</label>
                    <input
                    type="text"
                    placeholder="00-0"
                    name="workexperiences[{{ $index }}][paygrade]"
                    value="{{ old("workexperiences.{$index}.paygrade", $workexperience['paygrade']) }}"
                    class="{{ $errors->has("workexperiences.{$index}.paygrade") ? 'border-red-400' : '' }}" />
                </div>

                <div class="col-span-4">
                    <label for="workexperiences[{{ $index }}][appointmentstatus]">Appointment Status</label>
                    <select
                    name="workexperiences[{{ $index }}][appointmentstatus]"
                    class="{{ $errors->has("workexperiences.{$index}.appointmentstatus") ? 'border-red-400' : '' }}">
                        @foreach ($appointmentStatuses as $status)
                            <option value="{{ $status }}" @selected($status === old("workexperiences.{$index}.appointmentstatus") || $status === $workexperience['appointmentstatus'])>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="col-span-2">
                    <label for="workexperiences[{{ $index }}][govtservice]">Government Service</label>
                    <select
                    name="workexperiences[{{ $index }}][govtservice]"
                    class="{{ $errors->has("workexperiences.{$index}.govtservice") ? 'border-red-400' : '' }}">
                        <option value="1" @selected($workexperience['govtservice'] == 1)>
                            Yes
                        </option>
                        <option value="0" @selected($workexperience['govtservice'] == 0)>
                            No
                        </option>
                    </select>
                </div> --}}
            </div>
        @endforeach
    </div>
</div>

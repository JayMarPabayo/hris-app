<div>
    <div class="flex items-center gap-x-2 mb-3">
        <h3 class="text-sm font-normal text-slate-400">Education</h3>
        <button type="button" class="btn-add" wire:click.prevent="addEducation">✚</button>
    </div>
    <div class="flex flex-col gap-y-2 mb-2 pb-5 border-b border-slate-300">
        @foreach ($educations as $index => $education)
            <div class="grid grid-cols-12 gap-y-1 gap-x-2 py-4 px-3 border-b border-slate-300 bg-green-600/10 shadow-sm rounded-md">
                
                <div class="col-span-3">
                    <label for="level">School Level</label>
                    <input
                    type="text"
                    placeholder="Level"
                    name="education[{{ $index }}][level]"
                    value="{{ old("education.{$index}.level", $education['level']) }}"
                    @class(['border-red-400' => $errors->has("education.{$index}.level")]) />
                </div>

                <div class="col-span-4">
                    <label for="school">Name of School</label>
                    <input
                    type="text"
                    name="education[{{ $index }}][school]"
                    placeholder="School Name"
                    value="{{ old("education.{$index}.school", $education['school']) }}"
                    @class(['border-red-400' => $errors->has("education.{$index}.school")]) />
                </div>

                <div class="col-span-4">
                    <label for="degree">Basic Education/Degree/Course</label>
                    <input
                    type="text"
                    name="education[{{ $index }}][degree]"
                    placeholder="Write in full"
                    value="{{ old("education.{$index}.degree", $education['degree']) }}"
                    @class(['border-red-400' => $errors->has("education.{$index}.degree")]) />
                </div>

                <div class="row-span-2 w-fit ml-auto flex justify-end items-center">
                    <button type="button" title="Remove" class="btn" wire:click.prevent="removeEducation({{ $index }})">
                        <x-carbon-trash-can class="w-4 mx-auto"/>
                    </button>
                </div>

                <div class="col-span-1">
                    <label for="start">Start</label>
                    <input
                    type="year"
                    placeholder="Year"
                    name="education[{{ $index }}][start]"
                    title="Start"
                    value="{{ old("education.{$index}.start", $education['start']) }}"
                    class="@error("education.{$index}.start") border-red-400 @enderror" />
                </div>

                <div class="col-span-1">
                    <label for="end">End</label>
                    <input
                    type="year"
                    placeholder="Year"
                    name="education[{{ $index }}][end]"
                    title="End"
                    value="{{ old("education.{$index}.end", $education['end']) }}"
                    class="@error("education.{$index}.end") border-red-400 @enderror" />
                </div>

                <div class="col-span-3">
                    <label for="earned">Highest Leve/Units Earned</label>
                    <input
                    type="text"
                    name="education[{{ $index }}][earned]"
                    placeholder="Level or Units"
                    value="{{ old("education.{$index}.earned", $education['earned']) }}"
                    @class(['border-red-400' => $errors->has("education.{$index}.earned")]) />
                </div>

                <div class="col-span-2">
                    <label for="graduated">Year Graduated</label>
                    <input
                    type="year"
                    placeholder="Year"
                    name="education[{{ $index }}][graduated]"
                    title="Graduated"
                    value="{{ old("education.{$index}.graduated", $education['graduated']) }}"
                    class="@error("education.{$index}.graduated") border-red-400 @enderror" />
                </div>

                <div class="col-span-4">
                    <label for="accolades">Scholarships/Academic Honors Recieved</label>
                    <input
                    type="text"
                    placeholder="Awards"
                    name="education[{{ $index }}][accolades]"
                    value="{{ old("education.{$index}.accolades", $education['accolades']) }}"
                    @class(['border-red-400' => $errors->has("education.{$index}.accolades")]) />
                </div>

            </div>
        @endforeach

    </div>
</div>
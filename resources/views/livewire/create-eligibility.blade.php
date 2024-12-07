<div>
    <div class="flex items-center gap-x-2 mb-3">
        <h3 class="text-sm font-normal text-teal-400">Government Employment Eligibilities</h3>
        <button type="button" class="btn-add" wire:click.prevent="addEligibility">✚</button>
    </div>

    <div class="flex flex-col gap-y-2 mb-2 pb-5 border-b border-slate-300">
        @foreach ($eligibilities as $index => $eligibility)
            <div class="flex items-center gap-x-2">
                <div class="w-60">
                    <label for="examination">Examination</label>
                    <input
                    type="text"
                    placeholder="Career Service/RA 1080 (BOARD/BAR)..."
                    name="eligibilities[{{ $index }}][examination]"
                    value="{{ old("eligibilities.{$index}.examination", $eligibility['examination']) }}"
                    @class(['border-red-400' => $errors->has("eligibilities.{$index}.examination")]) />
                </div>

                <div class="w-20">
                    <label for="rating">Rating</label>
                    <input
                    type="number"
                    placeholder="00.00"
                    step=".01"
                    name="eligibilities[{{ $index }}][rating]"
                    value="{{ old("eligibilities.{$index}.rating", $eligibility['rating']) }}"
                    @class(['border-red-400' => $errors->has("eligibilities.{$index}.rating")]) />
                </div>

                <div class="w-32">
                    <label for="examdate">Date Taken</label>
                    <input type="date"
                    name="eligibilities[{{ $index }}][examdate]"
                    value="{{ old("eligibilities.{$index}.examdate", $eligibility['examdate']) }}"
                    @class(['border-red-400' => $errors->has("eligibilities.{$index}.examdate")]) />
                </div>

                <div class="grow">
                    <label for="address">Address</label>
                    <input
                    type="text"
                    placeholder="Address"
                    name="eligibilities[{{ $index }}][address]"
                    value="{{ old("eligibilities.{$index}.address", $eligibility['address']) }}"
                    @class(['border-red-400' => $errors->has("eligibilities.{$index}.address")]) />
                </div>

                <div class="w-32">
                    <label for="license">License</label>
                    <input
                    type="text"
                    placeholder="No."
                    name="eligibilities[{{ $index }}][license]"
                    value="{{ old("eligibilities.{$index}.license", $eligibility['license']) }}"
                    @class(['border-red-400' => $errors->has("eligibilities.{$index}.license")]) />
                </div>

                <div class="w-24">
                    <label for="validity">Validity</label>
                    <input
                    type="year"
                    placeholder="Year"
                    name="eligibilities[{{ $index }}][validity]"
                    title="Validity"
                    value="{{ old("eligibilities.{$index}.validity", $eligibility['validity']) }}"
                    class="@error("eligibilities.{$index}.validity") border-red-400 @enderror" />
                </div>

                <div class="col-span-1 mt-auto">
                    <button type="button" title="Remove" class="btn-remove" wire:click.prevent="removeEligibility({{ $index }})">
                        <x-carbon-trash-can class="w-4 mx-auto"/>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
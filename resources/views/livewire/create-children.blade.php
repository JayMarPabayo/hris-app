<div>
    <div class="flex items-center gap-x-2 mb-3">
        <h3 class="text-sm font-normal text-teal-400">Children</h3>
        <button type="button" class="btn-add" wire:click.prevent="addChild">✚</button>
    </div>
    <div class="flex flex-col gap-y-2 mb-2 pb-5 border-b border-slate-300">
        @foreach ($children as $index => $child)
            <div class="flex items-center gap-x-2">
                <input type="text"
                name="children[{{ $index }}][fullname]"
                placeholder="Full name"
                value="{{ old("children.{$index}.fullname", $child['fullname']) }}"
                @class(['border-red-400' => $errors->has("children.{$index}.fullname")]) />

                <div class="w-1/4">
                    <select
                    name="children[{{ $index }}][gender]"
                    @class(['border-red-400' => $errors->has("children.{$index}.gender")]) >
                        <option value="Male"
                        @selected(old("children.{$index}.gender") === "Male" || $child['gender']  === "Male")>
                            Male
                        </option>
                        <option value="Female" 
                        @selected(old("children.{$index}.gender") === "Female" || $child['gender']  === "Female")>
                            Female
                        </option>
                    </select>
                </div>
    
                <input type="date"
                name="children[{{ $index }}][birthdate]"
                title="Birthdate"
                value="{{ old("children.{$index}.birthdate", $child['birthdate']) }}"
                class="w-1/4 @error("children.{$index}.birthdate") border-red-400 @enderror" />
                
                <button type="button" title="Remove" class="btn-remove" wire:click.prevent="removeChild({{ $index }})">
                    <x-carbon-trash-can class="w-4 mx-auto"/>
                </button>
            </div>
        @endforeach
    </div>
</div>

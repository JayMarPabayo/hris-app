<div x-data="{ openAdd:false }">
    <button @click.prevent="openAdd = true" title="Add New Shift" class="btn mb-2">Add New ✚</button>
    <div x-cloak x-show="openAdd" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
        <div class="w-80 bg-white pt-4 px-6 pb-3 rounded-lg">
            <form
            id="add-shift-form"
            action="{{ route('shifts.store') }}"
            method="POST"
            >
                @csrf
                <h5 class="text-sm mb-4">Add Shift</h5>

                <input type="text" name="name"
                placeholder="Shift Name"
                value="{{ old('name') }}"
                class="mb-4"/>

                <div class="mb-4 border border-slate-200 p-2 grid grid-cols-2 gap-2">
                    @foreach($weekdays as $day)
                        <label class="flex justify-between items-center py-1 px-3 bg-slate-200 rounded-md shadow-sm cursor-pointer">
                            <span class="text-xs font-medium">{{ $day }}</span>
                            <input 
                                type="checkbox" 
                                name="weekdays[]" 
                                value="{{ $day }}"
                                class="w-fit focus:outline-none cursor-pointer"
                                {{ is_array(old('weekdays')) && in_array($day, old('weekdays')) ? 'checked' : '' }}
                            />
                        </label>
                    @endforeach
                </div>

                <input 
                    type="time" 
                    name="start_time"
                    value="{{ old('start_time') }}"
                    class="mb-4" />

                <input 
                    type="time" 
                    name="end_time"
                    value="{{ old('end_time') }}"
                    class="mb-4" />

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                    <button type="button" @click="openAdd = false" class="btn">
                        Cancel
                    </button>
                    <button
                    type="submit"
                    class="btn"
                    x-on:click="submitting=true; document.getElementById('add-shift-form').submit();"
                    >
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div> 
</div>
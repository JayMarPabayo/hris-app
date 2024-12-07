<x-layout>
    <h3 class="text-base font-semibold mb-3 text-white">Administration</h3>
    <div class="flex gap-x-2">
        @include('layouts.navbar')
        <main class="grow">
            <div class="flex justify-start">
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
            </div>
                @if ($shifts)
                <table class="text-xs shift-table">
                    <thead>
                        <tr class="bg-slate-300">
                            
                            <th style="text-align: start">Name</th>
                            <th>Weekdays</th>
                            <th>Time</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $colors = [
                            'bg-rose-600/60',
                            'bg-blue-600/60',
                            'bg-amber-600/60',
                            'bg-teal-600/60',
                            'bg-stone-600/60',
                            'bg-cyan-600/60',
                            'bg-zinc-600/60',
                            'bg-pink-600/60',
                            'bg-purple-600/60',
                            'bg-yellow-600/60',
                            'bg-green-600/60',
                            'bg-red-600/60',
                            'bg-gray-600/60',
                            'bg-indigo-600/60',
                            'bg-lime-600/60',
                            'bg-fuchsia-600/60',
                            'bg-violet-600/60',
                            'bg-sky-600/60',
                            'bg-orange-600/60',
                            'bg-emerald-600/60',
                            'bg-rose-500/60',
                            'bg-blue-500/60',
                            'bg-amber-500/60',
                            'bg-teal-500/60',
                            'bg-stone-500/60',
                            'bg-cyan-500/60',
                            'bg-zinc-500/60',
                            'bg-pink-500/60',
                            'bg-purple-500/60',
                            'bg-yellow-500/60',
                            'bg-green-500/60',
                            'bg-red-500/60',
                            'bg-gray-500/60',
                            'bg-indigo-500/60',
                            'bg-lime-500/60',
                            'bg-fuchsia-500/60',
                            'bg-violet-500/60',
                            'bg-sky-500/60',
                            'bg-orange-500/60',
                            'bg-emerald-500/60'
                        ];
                        @endphp
                        @forelse ($shifts as $index => $shift)
                            @php
                                $startTime = new DateTime($shift->start_time);
                                $endTime = new DateTime($shift->end_time);
                                $colorClass = $colors[$index % count($colors)];
                            @endphp
                            <tr class="border-b border-slate-400/50 cursor-pointer bg-slate-200/40 hover:bg-slate-200/30 duration-300">
                                    <td class="flex items-center font-medium">
                                        {{ $shift->name }}
                                    </td>
                                    <td>
                                        <div class="flex gap-x-2 justify-center items-center">
                                            @foreach ($shift->weekdays as $day)
                                                <div class="time-style {{ $colorClass }}" style="margin-inline: 0">
                                                    {{ strtoupper(substr($day, 0, 3)) }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="time-style {{ $colorClass }}">
                                            {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                                        </div>
                                    </td>
                                    <td class="flex gap-x-2 justify-center">
                                        {{-- EDIT BUTTON --}}
                                        <div x-data="{ openEdit: false }">
                                            <button type="button" @click.prevent="openEdit = true" title="Delete" class="btn-add" style="padding: 0.3rem 0.8rem">
                                                <x-carbon-pen class="w-4 mx-auto"/>
                                            </button>
                                            <div x-cloak x-show="openEdit" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                                <div class="w-96 bg-white pt-4 px-6 pb-3 rounded-lg">
                                                    <form
                                                    id="update-shift-form-{{ $index }}"
                                                    action="{{ route('shifts.update', $shift) }}"
                                                    method="POST"
                                                    >
                                                        @csrf
                                                        @method("PUT")
                                                        <div class="flex items-center gap-x-2 mb-5 text-slate-600 text-sm">
                                                            <x-ionicon-create class="h-5 fill-teal-600" />
                                                            <p>Update Shift</p>
                                                        </div>
                                                        <input type="text" name="name"
                                                        placeholder="Shift Name"
                                                        value="{{  $shift->name }}"
                                                        class="mb-4"/>
                        
                                                        <div class="mb-4 border border-slate-200 p-2 grid grid-cols-2 gap-2">
                                                            @foreach($weekdays as $day)
                                                                <label class="flex justify-between items-center py-1 px-3 bg-slate-500/70 rounded-md shadow-sm cursor-pointer">
                                                                    <span class="text-xs font-medium">{{ $day }}</span>
                                                                    <input 
                                                                        type="checkbox" 
                                                                        name="weekdays[]" 
                                                                        value="{{ $day }}"
                                                                        class="w-fit focus:outline-none cursor-pointer"
                                                                        {{ is_array($shift->weekdays) && in_array($day, $shift->weekdays) ? 'checked' : '' }}
                                                                    />
                                                                </label>
                                                            @endforeach
                                                        </div>
                        
                                                        <input 
                                                            type="time" 
                                                            name="start_time"
                                                            value="{{ $shift->start_time }}"
                                                            class="mb-4" />
                        
                                                        <input 
                                                            type="time" 
                                                            name="end_time"
                                                            value="{{ $shift->end_time }}"
                                                            class="mb-4" />
                        
                                                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                                            <button type="button" @click="openEdit = false" class="btn w-32">
                                                                Cancel
                                                            </button>
                                                            <button
                                                            type="submit"
                                                            class="btn-submit w-32"
                                                            x-on:click="submitting=true; document.getElementById('update-shift-form-{{ $index }}').submit();"
                                                            >
                                                                Update
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
    
                                        {{-- DELETE BUTTON --}}
                                        <div x-data="{ openDelete: false }">
                                            <button type="button" @click.prevent="openDelete = true" title="Delete" class="btn-add" style="padding: 0.3rem 0.8rem">
                                                <x-carbon-trash-can class="w-4 mx-auto"/>
                                            </button>
                                            <div x-cloak x-show="openDelete" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                                <div class="bg-white pt-4 px-6 pb-3 rounded-lg">
                                                    <p class="text-sm">Are you sure you want to delete this shift?</p>
                                                    <p class="mb-4 text-slate-400 text-xs">Deleting this shift will also remove schedules that are associated with it.</p>
                                                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                                        <button @click="openDelete = false" class="btn">No</button>
                                                        <form
                                                        id="delete-shift-form-{{ $index }}"
                                                        action="{{ route('shifts.destroy', $shift) }}"
                                                        method="POST"
                                                        >
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                            type="submit"
                                                            class="btn"
                                                            x-on:click="submitting=true; document.getElementById('delete-shift-form-{{ $index }}').submit();"
                                                            >Yes</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
            @endif
            </div>
        </main>
    </div>
</x-layout>
<x-layout>
    <div class="flex gap-x-2 justify-between items-center mb-5">
        <h3 class="text-base font-semibold">Evaluations</h3>
        <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-calendar class="h-5" />
            <a href="{{ route('evaluations.monthly.index') }}" class="border-none bg-none underline">
                Monthly Results
            </a>
        </div>
        
    </div>

    <form method="GET" action="{{ route('evaluations.index') }}" class="flex items-center gap-2 mb-4">
        <input type="search" placeholder="Search..." name="search" value="{{ request('search') }}" class="flex-grow">
        <input type="hidden" name="sort" value="{{ request('sort') === 'desc' ? 'asc' : 'desc' }}">
        <input type="week" name="week" value="{{ request('week') ?? $currentWeek }}" class="w-48 btn">
        <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">Search<span class="text-lg leading-3">⌕</span></button>
        <a href="{{ route('evaluations.create') }}" class="btn w-32">Add New ✚</a>
    </form>

        <table>
            <thead>
                <tr class="bg-slate-300">
                    <th>ID</th>
                    <th>Employee</th>
                    <th class="flex items-center justify-between py-1">
                        <span>Rating</span>
                        <form action="{{ route('evaluations.index') }}" method="GET">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="week" value="{{ request('week') }}">
                            <input type="hidden" name="sort" value="{{ request('sort') === 'desc' ? 'asc' : 'desc' }}">
                            <button type="submit" class="flex flex-col me-2" style="font-size: 0.6rem;">
                                <span class="cursor-pointer {{ request('sort') === 'desc' ? 'text-cyan-600' : '' }}">▲</span>
                                <span class="cursor-pointer {{ request('sort') === 'asc' ? 'text-cyan-600' : '' }}">▼</span>
                            </button>
                        </form>
                    </th>
                    <th>Evaluations/Remarks</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($evaluations as $index => $evaluation)
                    <tr class="data-row">
                        <td>{{ $evaluation->employee_id }}</td>
                        <td class="max-w-32">
                            <div class="text-sm font-medium">{{ "{$evaluation->employee->lastname}, {$evaluation->employee->firstname} " . strtoupper(substr($evaluation->employee->middlename, 0, 1)) . "." }}</div>
                            <span class="block text-teal-700">
                                {{ $evaluation->employee->department->name }}
                            </span>
                            <span class="block text-slate-500/70 truncate">
                                {{ $evaluation->employee->designation }}
                            </span>
                        </td>
                        <td class="max-w-[15rem]">
                            <x-rating-stars rating="{{ $evaluation->rating }}" />
                        </td>
                        <td class="max-w-sm pe-5">
                            <p style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                                {!! nl2br(e($evaluation->review)) !!}
                            </p>
                        </td>
                        <td class="flex justify-center items-center gap-x-1 px-2" style="margin-top: 0.5rem;">
                            {{-- EDIT BUTTON --}}
                            <div x-data="{ openEdit: false }">
                                <button type="button" @click.prevent="openEdit = true" title="Delete" class="btn-add" style="padding: 0.3rem 0.8rem;">
                                    <x-carbon-pen class="w-4 mx-auto"/>
                                </button>
                                <div x-cloak x-show="openEdit" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                    <div class="min-w-96 max-w-fit bg-white pt-4 px-6 pb-3 rounded-lg">
                                        <form
                                        id="update-evaluation-form-{{ $index }}"
                                        action="{{ route('evaluations.update', $evaluation) }}"
                                        method="POST"
                                        >
                                            @csrf
                                            @method("PUT")
                                            <h5 class="text-sm mb-4">Update Evaluation</h5>
    
                                            <div class="px-2 py-1 rounded-sm bg-stone-600/20 font-medium mb-5">
                                                <span class="block">
                                                    {{ "{$evaluation->employee->lastname}, {$evaluation->employee->firstname} " . strtoupper(substr($evaluation->employee->middlename, 0, 1)) . "." }}
                                                </span>
                                                <span class="text-teal-700">
                                                    {{ $evaluation->employee->department->name }}
                                                </span>
                                                /
                                                <span class="text-slate-500/70">
                                                    {{ $evaluation->employee->designation }}
                                                </span>
                                            </div>
    
                                            <label for="rating">Rating</label>
                                            <select name="rating" class="mb-5">
                                                @for ($i = 1; $i <= 20; $i++)
                                                    <option value="{{ number_format($i / 2, 2) }}" @selected($evaluation->rating == number_format($i / 2, 2))>
                                                        {{ number_format($i / 2, 2) }}
                                                    </option>
                                                @endfor
                                            </select>
    
                                            <label class="block" for="review">Remarks</label>
                                            <textarea name="review" rows="5" class="mb-5 w-full border border-slate-200 px-2 py-1">{{ $evaluation->review }}</textarea>
    
                                            <input type="hidden" name="employee_id" value="{{ $evaluation->employee_id }}">
                            
            
                                            <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                                <button type="button" @click="openEdit = false" class="btn">
                                                    Cancel
                                                </button>
                                                <button
                                                type="submit"
                                                class="btn"
                                                x-on:click="submitting=true; document.getElementById('update-evaluation-form-{{ $index }}').submit();"
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
                                <button type="button" @click.prevent="openDelete = true" title="Delete" class="btn-add" style="padding: 0.3rem 0.8rem;">
                                    <x-carbon-trash-can class="w-4 mx-auto"/>
                                </button>
                                <div x-cloak x-show="openDelete" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                    <div class="bg-white pt-4 px-6 pb-3 rounded-lg">
                                        <p class="text-sm">Are you sure you want to delete this Evaluation?</p>
                                        <div class="flex justify-end gap-2 mt-3 pt-3 border-t border-slate-200">
                                            <button @click="openDelete = false" class="btn">No</button>
                                            <form
                                            id="delete-evaluation-form-{{ $index }}"
                                            action="{{ route('evaluations.destroy', $evaluation) }}"
                                            method="POST"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                type="submit"
                                                class="btn"
                                                x-on:click="submitting=true; document.getElementById('delete-evaluation-form-{{ $index }}').submit();"
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
                        <td colspan="100%" class="py-20">
                            <x-empty-alert />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @if ($evaluations->count())
        <div class="text-xs mt-4">
            {{ $evaluations->links()}}
        </div>
    @endif
</x-layout>
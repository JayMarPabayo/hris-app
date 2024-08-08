<x-layout>
    <h3 class="text-base font-semibold mb-3">Add Review</h3>

    <form method="GET" action="{{ route('evaluations.create') }}" class="flex items-center gap-2 mb-4">
        <input type="search" placeholder="Search..." name="search" value="{{ request('search') }}" class="flex-grow">
        <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">Search<span class="text-lg leading-3">⌕</span></button>
    </form>
    <table>
        <thead>
            <tr class="bg-slate-300">
                <th>ID</th>
                <th>Employee</th>
                <th>Department/Designation</th>
                <th>Evaluation</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $index => $employee)
                <tr class="data-row">
                    <td>
                        {{ $employee->id }}
                    </td>
                    <td>
                        {{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}
                    </td>
                    <td>
                        <span class="block text-teal-700">
                            {{ $employee->department->name }}
                        </span>
                        <span class="block text-slate-500/70">
                            {{ $employee->designation }}
                        </span>
                    </td>
                    <td>
                        <div x-data="{ openAddSchedule: false }">
                            <button @click.prevent="openAddSchedule = true" class="btn">
                                ✚
                            </button>
                            <div x-cloak x-show="openAddSchedule" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                <div class="w-96 bg-white pt-4 px-6 pb-3 rounded-lg">
                                    <form
                                    id="add-evaluation-form"
                                    action="{{ route('evaluations.store') }}"
                                    method="POST"
                                    >
                                        @csrf
                                        <h5 class="text-sm mb-4">Add Review</h5>

                                        <div class="px-2 py-1 rounded-sm bg-stone-600/20 font-medium mb-5">
                                            <span class="block">
                                                {{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}
                                            </span>
                                            <span class="text-teal-700">
                                                {{ $employee->department->name }}
                                            </span>
                                            /
                                            <span class="text-slate-500/70">
                                                {{ $employee->designation }}
                                            </span>
                                        </div>

                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                                        <label for="rating">Rating</label>
                                        <select name="rating" class="mb-5">
                                            @for ($i = 1; $i <= 20; $i++)
                                                <option value="{{ number_format($i / 2, 2) }}" {{ $i == 4 ? 'selected' : '' }}>{{ number_format($i / 2, 2) }}</option>
                                            @endfor
                                        </select>

                                        <label class="block" for="review">Remarks</label>
                                        <textarea name="review" rows="5" class="mb-5 w-full border border-slate-200 px-2 py-1"></textarea>

                                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                            <button type="button" @click="openAddSchedule = false" class="btn">
                                                Cancel
                                            </button>
                                            <button
                                            type="submit"
                                            class="btn"
                                            x-on:click="submitting=true; document.getElementById('add-evaluation-form').submit();"
                                            >
                                                Submit
                                            </button>
                                        </div>
                                    </form>
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
    <i class="text-xs text-slate-500 italic mt-2">
        List of employees with no reviews yet.
    </i>
    @if ($employees->count())
        <div class="text-xs mt-2">
            {{ $employees->links()}}
        </div>
    @endif
    <div class="flex justify-end gap-3 items-center pt-3">
        <a href="{{ route('evaluations.index') }}" class="btn"><span class="mx-4">Go back</span></a>
    </div>
</x-layout>

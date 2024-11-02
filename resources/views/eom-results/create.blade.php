<x-layout>
    <div class="flex gap-x-2 justify-between items-center mb-5">
        <h3 class="text-base font-semibol">Employee of the Month</h3>
        <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-user-profile class="h-5" />
            <a href="{{ route('profile.index') }}" class="border-none bg-none underline">
                Profile
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('employee-of-the-month.create') }}" class="flex items-center gap-2 mb-4">
        <input type="search" placeholder="Search..." name="search" value="{{ request('search') }}" class="flex-grow">
        <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">Search<span class="text-lg leading-3">⌕</span></button>
    </form>
    <table>
        <thead>
            <tr class="bg-slate-300">
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th class="text-center">
                    <p>Employee of the Month</p>
                    @if ($userVoted)
                    <p class="font-normal italic text-amber-700">Voted ✔</p>
                    @endif
                </th>
                <th class="text-center text-red-600">
                    <p>Least Effective Employee of the Month</p>
                    @if ($userNegativeVoted)
                    <p class="font-normal italic text-amber-700">Voted ✔</p>
                    @endif
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $index => $employee)
                <tr class="data-row">
                    <td>{{ $employee->id }}</td>
                    <td>{{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}</td>
                    <td>{{ $employee->department->name }}</td>
                    <td>{{ $employee->designation }}</td>
                    <td class="text-center">
                        <div x-data="{ openEdit: false }">
                            <button type="button" @disabled($userVoted) title="Vote" @click.prevent="openEdit = true" title="Vote" class="btn-add" style="padding: 0.3rem 0.8rem;">
                                <x-carbon-touch-1 title="Vote" class="w-4 mx-auto"/>
                            </button>
                            <div x-cloak x-show="openEdit" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                <div class="bg-white pt-4 px-6 pb-3 rounded-lg">
                                    <form
                                    id="vote-eom-form-{{ $employee->id }}"
                                    action="{{ route('employee-of-the-month.store') }}"
                                    method="POST"
                                    >
                                        @csrf
                                        <h5 class="text-sm mb-4">Employee of the Month Voting</h5>

                                        <div class="px-2 py-1 rounded-sm bg-stone-600/20 font-medium mb-3">
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
                                        <input type="hidden" name="month" value="{{ date('Y-m') }}">

                                        <div class="flex justify-end gap-x-4 pt-3 border-t border-slate-200">
                                            <button type="button" @click="openEdit = false" class="btn w-52 shadow-md">
                                                Cancel
                                            </button>
                                            <button
                                            type="submit"
                                            class="btn w-52 shadow-md"
                                            x-on:click="submitting=true; document.getElementById('vote-eom-form-{{ $employee->id }}').submit();"
                                            >
                                                Submit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div x-data="{ openEdit: false }">
                            <button type="button" @disabled($userNegativeVoted) title="Vote" @click.prevent="openEdit = true" title="Vote" class="btn-add" style="padding: 0.3rem 0.8rem;">
                                <x-carbon-touch-1 title="Vote" class="w-4 mx-auto"/>
                            </button>
                            <div x-cloak x-show="openEdit" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                <div class="bg-white pt-4 px-6 pb-3 rounded-lg">
                                    <form
                                    id="vote-neom-form-{{ $employee->id }}"
                                    action="{{ route('negative.voting') }}"
                                    method="POST"
                                    >
                                        @csrf
                                        <h5 class="text-sm mb-4">Least Effective Employee of the Month Voting</h5>

                                        <div class="px-2 py-1 rounded-sm bg-stone-600/20 font-medium mb-3">
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
                                        <input type="hidden" name="month" value="{{ date('Y-m') }}">

                                        <div class="flex justify-end gap-x-4 pt-3 border-t border-slate-200">
                                            <button type="button" @click="openEdit = false" class="btn w-52 shadow-md">
                                                Cancel
                                            </button>
                                            <button
                                            type="submit"
                                            class="btn w-52 shadow-md"
                                            x-on:click="submitting=true; document.getElementById('vote-neom-form-{{ $employee->id }}').submit();"
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
    @if ($employees->count())
        <div class="text-xs mt-4">
            {{ $employees->links()}}
        </div>
    @endif
    
</x-layout>

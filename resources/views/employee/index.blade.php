<x-layout>
    <h3 class="text-base font-semibold mb-3 text-white">Employees</h3>
    <form method="GET" action="{{ route('employees.index') }}" class="flex items-center gap-2 mb-4" id="searchForm">
        <input 
            type="search" 
            placeholder="Search..." 
            name="search" 
            value="{{ request('search') }}" 
            class="flex-grow"
            oninput="document.getElementById('searchForm').submit()">
        {{-- <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">Search<span class="text-lg leading-3">⌕</span></button> --}}
        <a href="{{ route('employees.create') }}" class="btn w-32">Add New ✚</a>
    </form>

    <table>
        <thead>
            <tr class="bg-slate-300">
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Contact Details</th>
                <th>Leave Credits</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $index => $employee)
                <tr class="data-row">
                    <td>{{ $employee->id }}</td>
                    <td>{{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}</td>
                    <td>{{ $employee->department->name }}</td>
                    <td>{{ $employee->designation }}</td>
                    <td>{{ $employee->email ? "⎙ {$employee->email}" : ($employee->mobile ? "✆ {$employee->mobile}" : "") }}</td>
                    <td>{{ $employee->getRemainingCredits() }}</td>
                    <td class="flex justify-center items-center w-fit mx-auto p-1 gap-1">
                        <a title="View" href="{{ route('employees.show', $employee) }}" class="btn flex-grow p-0">
                            <x-carbon-view class="w-4 mx-auto"/>
                        </a>
                        <a title="Edit" href="{{ route('employees.edit', $employee) }}" class="btn flex-grow p-0">
                            <x-carbon-pen class="w-4 mx-auto"/>
                        </a>
                        <div x-data="{ open: false }">
                            <button @click.prevent="open = true" title="Delete" class="btn">
                                <x-carbon-trash-can class="w-4 mx-auto"/>
                            </button>
                            <div x-cloak x-show="open" class="fixed inset-0 flex bg-black backdrop-blur-sm bg-opacity-50">
                                <div class="bg-white pt-4 px-4 pb-3 rounded-lg h-fit mx-auto mt-[10%]">
                                    <div class="flex items-center gap-x-2 mb-5 text-slate-600">
                                        <x-ionicon-trash-bin-sharp class="h-5 fill-rose-500" />
                                        <p>Are you sure you want to delete this employee?</p>
                                    </div>
                                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-300 ps-10">
                                        <button type="button" @click="open = false" class="btn w-32">No</button>
                                        <form
                                        id="delete-employee-form-{{ $index }}"
                                        action="{{ route('employees.destroy', $employee) }}"
                                        method="POST"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                            type="submit"
                                            class="btn-delete w-32"
                                            x-on:click="submitting=true; document.getElementById('delete-employee-form-{{ $index }}').submit();"
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
    @if ($employees->count())
        <div class="text-xs mt-4">
            {{ $employees->links()}}
        </div>
    @endif
</x-layout>

<x-layout>
    <h3 class="text-base font-semibold mb-3">Administration</h3>
    <div class="flex gap-x-2">
        @include('layouts.navbar')
        <main class="grow">
            <div class="flex justify-start">
                <div x-data="{ openAdd: false }">
                    <button @click.prevent="openAdd = true" title="Add New Department" class="btn mb-2">Add New ✚</button>
                    <div x-cloak x-show="openAdd" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                        <div class="w-80 bg-white pt-4 px-6 pb-3 rounded-lg">
                            <form
                            id="add-department-form"
                            action="{{ route('departments.store') }}"
                            method="POST"
                            >
                                @csrf
                                <h5 class="text-sm mb-4">Add Department</h5>
                                <input type="text" name="name"
                                placeholder="Department Name"
                                value="{{ old('name') }}"
                                class="mb-4"/>
                                <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                    <button type="button" @click="openAdd = false" class="btn">
                                        Cancel
                                    </button>
                                    <button
                                    type="submit"
                                    class="btn"
                                    x-on:click="submitting=true; document.getElementById('add-department-form').submit();"
                                    >
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @if ($departments)
                <table class="text-xs">
                    <thead>
                        <tr class="bg-slate-300">
                            <th>ID</th>
                            <th>Name</th>
                            <th>No. of Employees</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $index => $department)
                            <tr class="data-row">
                                <td>{{ $department->id }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->employees_count }}</td>  
                                <td class="flex gap-x-2 justify-center">
                                    {{-- EDIT BUTTON --}}
                                    <div x-data="{ openEdit: false }">
                                        <button @click.prevent="openEdit = true" title="Delete" class="btn-add" style="padding: 0.3rem 0.8rem">
                                            <x-carbon-pen class="w-4 mx-auto"/>
                                        </button>
                                        <div x-cloak x-show="openEdit" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                            <div class="w-80 bg-white pt-4 px-6 pb-3 rounded-lg">
                                                <form
                                                id="update-department-form-{{ $index }}"
                                                action="{{ route('departments.update', $department) }}"
                                                method="POST"
                                                >
                                                    @csrf
                                                    @method('PUT')
                                                    <h5 class="text-sm mb-4">Update Department</h5>
                                                    <input type="text" name="name"
                                                    placeholder="Department Name"
                                                    value="{{ $department->name }}"
                                                    class="mb-4"/>
                                                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                                        <button type="button" @click="openEdit = false" class="btn">
                                                            Cancel
                                                        </button>
                                                        <button
                                                        type="submit"
                                                        class="btn"
                                                        x-on:click="submitting=true; document.getElementById('update-department-form-{{ $index }}').submit();"
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
                                        <button @click.prevent="openDelete = true" title="Delete" class="btn-add" style="padding: 0.3rem 0.8rem">
                                            <x-carbon-trash-can class="w-4 mx-auto"/>
                                        </button>
                                        <div x-cloak x-show="openDelete" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                            <div class="bg-white pt-4 px-6 pb-3 rounded-lg">
                                                <p class="text-sm">Are you sure you want to delete this department?</p>
                                                <p class="mb-4 text-slate-400 text-xs">Deleting this department will also remove employees that are associated with it.</p>
                                                <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                                    <button type="button" @click="openDelete = false" class="btn">No</button>
                                                    <form
                                                    id="delete-department-form-{{ $index }}"
                                                    action="{{ route('departments.destroy', $department) }}"
                                                    method="POST"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                        type="submit"
                                                        class="btn"
                                                        x-on:click="submitting=true; document.getElementById('delete-department-form-{{ $index }}   ').submit();"
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
        </main>
    </div>

</x-layout>
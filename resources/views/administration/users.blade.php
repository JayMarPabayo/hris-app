<x-layout>
    <h3 class="text-base font-semibold mb-3 text-white">Admins</h3>
    <div class="flex gap-x-2">
        @include('layouts.navbar')
        <main class="grow">
            <div class="flex justify-start">
                <div x-data="{ openAdd: false }">
                    <button @click.prevent="openAdd = true" title="Add New Admin" class="btn mb-2">Add New ✚</button>
                    <div x-cloak x-show="openAdd" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                        <div class="w-80 bg-white pt-4 px-6 pb-3 rounded-lg">
                            <form
                            id="add-department-form"
                            action="{{ route('users.store') }}"
                            method="POST"
                            >
                                @csrf
                                <div class="flex items-center gap-x-2 mb-5 text-slate-600 text-sm">
                                    <x-ionicon-shield-half class="h-5 fill-teal-600" />
                                    <p>Add Admin</p>
                                </div>

                                <input type="text" name="name"
                                placeholder="Full name"
                                value="{{ old('name') }}"
                                class="mb-4"/>

                                <input type="email" name="email"
                                placeholder="email@example.com"
                                value="{{ old('email') }}"
                                class="mb-4"/>

                                <input type="text" name="username"
                                placeholder="Username"
                                value="{{ old('username') }}"
                                class="mb-2"/>

                                <div class="rounded-sm bg-slate-400/50 p-2 mb-4 text-justify text-slate-600">
                                    ☛ A default password, "password," will be assigned to the account. The users will be able update it upon their login.
                                </div>

                                <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                    <button type="button" @click="openAdd = false" class="btn w-32">
                                        Cancel
                                    </button>
                                    <button
                                    type="submit"
                                    class="btn-submit w-32"
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
            @if ($admins)
                <table class="text-xs">
                    <thead>
                        <tr class="bg-slate-300">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $index => $admin)
                            <tr class="data-row">
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->username }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->created_at->format('F d, Y') }}</td>
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
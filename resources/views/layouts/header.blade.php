<header class="bg-slate-700 text-white py-4 px-32 font-normal tracking-wide">
    <div class="container mx-auto flex items-center justify-between">
        {{-- Logo and Menu --}}
        <div class="flex items-center space-x-4">
            {{-- Logo --}}
            <div class="flex-shrink-0">
                <a href="{{ route('employees.index') }}">
                    <img src="{{ asset('assets/logo.png') }}" alt="Website Logo" class="h-8 w-8">
                </a>
            </div>
            @auth
                {{-- Menu Items --}}
                <nav class="flex space-x-4">
                    <a href="{{ route('employees.index') }}"
                    class="hover:text-slate-400 duration-200
                    {{ request()->routeIs('employees.*') ? 'text-slate-400' : '' }}">
                        Employees
                    </a>
                    <a href="{{ route('schedules.index') }}"
                    class="hover:text-slate-400 duration-200
                    {{ request()->routeIs('schedules.*') ? 'text-slate-400' : '' }}">
                        Schedules
                    </a>
                    <a href="{{ route('evaluations.index') }}"
                    class="hover:text-slate-400 duration-200
                    {{ request()->routeIs('evaluations.*') ? 'text-slate-400' : '' }}">
                        Evaluations
                    </a>
                    <a href="{{ route('reports.index') }}"
                    class="hover:text-slate-400 duration-200
                    {{ request()->routeIs('reports.*') ? 'text-slate-400' : '' }}">
                        Reports
                    </a>
                    <a href="{{ route('administration.index') }}"
                    class="hover:text-slate-400 duration-200
                    {{ request()->routeIs('administration.*') ? 'text-slate-400' : '' }}">
                        Administration
                    </a>
                </nav>
            @endauth
            @if (!auth()->user())
                <h2>Human Resource Information System</h2>
            @endif
        </div>
        {{-- Username --}}
        <div class="flex items-center space-x-4">
            @auth
                <div class="flex items-center gap-x-2 me-2">
                    <x-carbon-user-avatar-filled-alt class="w-6" />
                    <span>{{ auth()->user()->name ?? 'Guest' }}</span>
                </div>
                <div x-data="{ openMenu: false }">
                    <div class="relative">
                        <button type="button" @click.prevent="openMenu = !openMenu" class="bg-none hover:scale-105 active:scale-95">•••</button>
                        <div x-cloak x-show="openMenu" @click.outside="openMenu = false" class="absolute top-[100%] right-0 bg-slate-100 w-40 rounded-sm">
                            <div class="flex flex-col gap-y-1 font-medium">
                                <div x-data="{ openAccount: false }">
                                    <button @click.prevent="openAccount = true" class="bg-none w-full text-slate-600 text-start px-3 py-1 rounded-sm hover:bg-slate-200 duration-300">
                                        Account
                                    </button>
                                    <div x-cloak x-show="openAccount" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                        <div class="w-80 bg-white pt-4 px-6 pb-3 rounded-lg text-slate-600">
                                            <form
                                            id="update-user-form"
                                            action="{{ route('auth.update', auth()->user()->id) }}"
                                            method="POST"
                                            >
                                                @csrf
                                                @method('PUT')

                                                <h5 class="text-sm mb-4">Account</h5>

                                                <label for="name">Full Name</label>
                                                <input type="text" name="name"
                                                placeholder="Name"
                                                value="{{ auth()->user()->name }}"
                                                class="mb-4"/>

                                                <label for="username">Username</label>
                                                <input type="text" name="username"
                                                placeholder="Username"
                                                value="{{ auth()->user()->username }}"
                                                class="mb-4"/>

                                                <label for="password">New Password</label>
                                                <input type="password" name="password"
                                                placeholder="New Password"
                                                autocomplete="off"
                                                value=""
                                                class="mb-4"/>

                                                <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                                    <button type="button" @click="openAccount = false" class="btn">
                                                        Cancel
                                                    </button>
                                                    <button
                                                    type="submit"
                                                    class="btn"
                                                    x-on:click="submitting=true; document.getElementById('update-user-form').submit();"
                                                    >
                                                        Update
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <form id="auth-logout-form" action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                    type="submit"
                                    x-on:click="submitting=true; document.getElementById('auth-logout-form').submit();"
                                    class="bg-none w-full text-slate-600 text-start px-3 py-1 rounded-sm hover:bg-slate-200 duration-300">
                                        Log out?
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>
<header class="bg-slate-700/90 text-white py-4 px-32 font-normal tracking-wide">
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

                @if (Auth::check() && Auth::user()->role === 'Administrator')
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
                        <a href="{{ route('requests.index') }}"
                        class="relative hover:text-slate-400 duration-200
                        {{ request()->routeIs('requests.*') ? 'text-slate-400' : '' }}">
                            <span>Leave Requests</span>
                            @if(App\Models\LeaveRequest::hasAnyPendingRequest())
                                <x-carbon-circle-solid class="h-3 fill-red-600 absolute -top-1 -right-1" />
                            @endif
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
                        {{ request()->routeIs('administration*') || request()->routeIs('shifts.index') ? 'text-slate-400' : '' }}">
                            Administration
                        </a>
                        
                    </nav>
                @else
                    <h2 class="font-engagement text-2xl tracking-wider">Human Resource Information System</h2>
                @endif
                
            @endauth
            @if (!auth()->user())
                <h2 class="font-engagement text-2xl tracking-wider">Human Resource Information System</h2>
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
                            <div class="flex flex-col gap-y-1 font-medium ">
                                <div x-data="{ openAccount: false }">
                                    <button @click.prevent="openAccount = true" class="bg-none w-full text-slate-600 text-start px-3 py-1 rounded-sm hover:bg-slate-200 duration-300">
                                        Account
                                    </button>
                                    <div x-cloak x-show="openAccount" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                        <div class="w-2/5 bg-slate-100/90 pt-4 px-6 pb-3 rounded-lg text-slate-600">
                                            <form
                                            id="update-user-form"
                                            action="{{ route('auth.update', auth()->user()->id) }}"
                                            method="POST"
                                            >
                                                @csrf
                                                @method('PUT')
                                
                                                <h3 class="text-base font-semibold mb-3 text-pink-800/80">Account</h3>

                                                <div class="h-1 border-t border-slate-600/50 mb-3"></div>
                                
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
                                
                                                <!-- Add Current Password -->
                                                <label for="current_password">Current Password</label>
                                                <input type="password" name="current_password"
                                                placeholder="Current Password"
                                                autocomplete="off"
                                                class="mb-4"
                                                value="{{ old('current_password') }}"/>
                                
                                                <!-- New Password -->
                                                <label for="password">New Password</label>
                                                <input type="password" name="password"
                                                placeholder="New Password"
                                                autocomplete="off"
                                                class="mb-4"
                                                value="{{ old('password') }}"/>
                                
                                                <!-- Confirm New Password -->
                                                <label for="password_confirmation">Re-enter New Password</label>
                                                <input type="password" name="password_confirmation"
                                                placeholder="Re-enter New Password"
                                                autocomplete="off"
                                                class="mb-4"
                                                value="{{ old('password_confirmation') }}"/>
                                
                                                <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                                    <button type="button" @click="openAccount = false" class="btn w-40">
                                                        Cancel
                                                    </button>
                                                    <button
                                                    class="btn w-40"
                                                    type="submit"
                                                    >
                                                        Update
                                                    </button>                                                                           
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div x-data="{ open: false }">
                                    <button @click.prevent="open = true" title="Delete" class="bg-none w-full text-slate-600 text-start px-3 py-1 rounded-sm hover:bg-slate-200 duration-300">
                                        Log out?
                                    </button>
                                    <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                        <div class="bg-white pt-4 w-1/4 px-3 pb-3 rounded-lg">
                                            <p class="mb-4 text-base text-rose-700/80">Are you sure you want to logout?</p>
                                            <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                                <button type="button" @click="open = false" class="btn w-36">No</button>
                                                <form id="auth-logout-form" action="{{ route('auth.logout') }}" method="POST" class="w-36">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                    type="submit"
                                                    x-on:click="submitting=true; document.getElementById('auth-logout-form').submit();"
                                                    class="btn w-full">
                                                        Yes
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const updateForm = document.getElementById("update-user-form");
    const passwordField = updateForm.querySelector("input[name='password']");
    const currentPasswordField = updateForm.querySelector("input[name='current_password']");
    const confirmPasswordField = updateForm.querySelector("input[name='password_confirmation']");

    const setErrorBorder = (field) => {
        field.classList.add("border-red-500");
        field.classList.add("outline-red-500");
    };

    const clearErrorBorder = (field) => {
        field.classList.remove("border-red-500");
        field.classList.remove("outline-red-500");
    };

    confirmPasswordField.addEventListener("input", () => {
        if (passwordField.value !== confirmPasswordField.value) {
            setErrorBorder(confirmPasswordField);
        } else {
            clearErrorBorder(confirmPasswordField);
        }
    })

    updateForm.addEventListener("submit", (event) => {
        let hasError = false;

        console.log("Submitting");

        if (passwordField.value) {
            // Validate Current Password
            if (!currentPasswordField.value) {
                setErrorBorder(currentPasswordField);
                hasError = true;
            } else {
                clearErrorBorder(currentPasswordField);
            }

            // Validate Confirm Password
            if (!confirmPasswordField.value) {
                setErrorBorder(confirmPasswordField);
                hasError = true;
            } else {
                clearErrorBorder(confirmPasswordField);
            }

            // Check if Password and Confirmation Match
            if (
                confirmPasswordField.value &&
                passwordField.value !== confirmPasswordField.value
            ) {
                setErrorBorder(confirmPasswordField);
                hasError = true;
            }
        }

        if (hasError) {
            event.preventDefault();
        }
    });
});

</script>

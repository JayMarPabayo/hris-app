<header class="text-slate-400 py-4 px-32 font-normal tracking-wide">
    <div class="container mx-auto flex items-center justify-between">
        {{-- Logo and Menu --}}
        <div class="flex items-center space-x-4">
            {{-- Logo --}}
            <div class="flex-shrink-0 me-8">
                <a href="{{ route('employees.index') }}">
                    <img src="{{ asset('assets/logo.png') }}" alt="Website Logo" class="h-12 w-12 hover:scale-105 duration-300">
                </a>
            </div>
            @auth
                {{-- Menu Items --}}

                @if (Auth::check() && Auth::user()->role === 'Administrator')
                    <nav class="flex space-x-7 text-sm">
                        <a href="{{ route('employees.index') }}"
                        class="hover:text-white hover:scale-105 duration-200 flex items-center gap-x-2
                        {{ request()->routeIs('employees.*') ? 'text-white' : '' }}">
                            <x-ionicon-people class="h-5" />
                            <div>Employees</div>
                        </a>
                        <a href="{{ route('schedules.index') }}"
                        class="hover:text-white hover:scale-105 duration-200 flex items-center gap-x-2
                        {{ request()->routeIs('schedules.*') ? 'text-white' : '' }}">
                            <x-ionicon-calendar class="h-5" />
                            <div>Schedules</div>
                        </a>
                        <a href="{{ route('requests.index') }}"
                        class="relative hover:text-white hover:scale-105 duration-200 flex items-center gap-x-2
                        {{ request()->routeIs('requests.*') ? 'text-white' : '' }}">
                            <x-ionicon-log-out-sharp class="h-5" />
                            <div>Leave Requests</div>
                            @if(App\Models\LeaveRequest::hasAnyPendingRequest())
                                <x-carbon-circle-solid class="h-3 fill-rose-500 absolute -top-1 -right-1" />
                            @endif
                        </a>
                        <a href="{{ route('evaluations.index') }}"
                        class="hover:text-white hover:scale-105 duration-200 flex items-center gap-x-2
                        {{ request()->routeIs('evaluations.*') ? 'text-white' : '' }}">
                            <x-ionicon-bar-chart-outline class="h-5" />
                            <div>Evaluations</div>
                        </a>
                        <a href="{{ route('reports.index') }}"
                        class="hover:text-white hover:scale-105 duration-200 flex items-center gap-x-2
                        {{ request()->routeIs('reports.*') ? 'text-white' : '' }}">
                            <x-ionicon-receipt-sharp class="h-5" />
                            <div>Reports</div>
                        </a>
                        <a href="{{ route('administration.index') }}"
                        class="hover:text-white hover:scale-105 duration-200 flex items-center gap-x-2
                        {{ request()->routeIs('administration*') || request()->routeIs('shifts.index') ? 'text-white' : '' }}">
                            <svg class="h-5" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="currentColor"><g><rect fill="none" height="24" width="24"></rect></g><g><g><path d="M17,11c0.34,0,0.67,0.04,1,0.09V6.27L10.5,3L3,6.27v4.91c0,4.54,3.2,8.79,7.5,9.82c0.55-0.13,1.08-0.32,1.6-0.55 C11.41,19.47,11,18.28,11,17C11,13.69,13.69,11,17,11z"></path><path d="M17,13c-2.21,0-4,1.79-4,4c0,2.21,1.79,4,4,4s4-1.79,4-4C21,14.79,19.21,13,17,13z M17,14.38c0.62,0,1.12,0.51,1.12,1.12 s-0.51,1.12-1.12,1.12s-1.12-0.51-1.12-1.12S16.38,14.38,17,14.38z M17,19.75c-0.93,0-1.74-0.46-2.24-1.17 c0.05-0.72,1.51-1.08,2.24-1.08s2.19,0.36,2.24,1.08C18.74,19.29,17.93,19.75,17,19.75z"></path></g></g></svg>
                            <div>Administration</div>
                        </a>
                        
                    </nav>
                @else
                    <h2 class="font-engagement text-2xl tracking-wider text-white">Human Resource Information System</h2>
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
                                        <div class="w-2/5 bg-white pt-4 px-6 pb-3 rounded-lg text-slate-600">
                                            <form
                                            id="update-user-form"
                                            action="{{ route('auth.update', auth()->user()->id) }}"
                                            method="POST"
                                            >
                                                @csrf
                                                @method('PUT')
                                
                                                <div class="flex items-center gap-x-2 mb-5 text-slate-600 text-sm">
                                                    <x-ionicon-shield-checkmark-sharp class="h-5 fill-teal-600" />
                                                    <p>Account</p>
                                                </div>

                                                <div class="h-1 border-t border-slate-600/50 mb-3"></div>
                                
                                                @if (Auth::user()->role === 'Administrator')
                                                    <label for="name" class="text-slate-500/80 font-medium">Full Name</label>
                                                    <input type="text" name="name"
                                                    placeholder="Name"
                                                    value="{{ auth()->user()->name }}"
                                                    class="mb-4"/>
                                                @else
                                                    <input type="hidden" name="name"
                                                    placeholder="Name"
                                                    value="{{ auth()->user()->name }}"
                                                    class="mb-4"/>
                                                @endif
                                                
                                
                                                <label for="username" class="text-slate-500/80 font-medium">Username</label>
                                                <input type="text" name="username"
                                                placeholder="Username"
                                                value="{{ auth()->user()->username }}"
                                                class="mb-4"/>
                                
                                                <!-- Add Current Password -->
                                                <label for="current_password" class="text-slate-500/80 font-medium">Current Password</label>
                                                <input type="password" name="current_password"
                                                placeholder="Current Password"
                                                autocomplete="off"
                                                class="mb-4"
                                                value="{{ old('current_password') }}"/>
                                
                                                <!-- New Password -->
                                                <label for="password" class="text-slate-500/80 font-medium">New Password</label>
                                                <input type="password" name="password"
                                                placeholder="New Password"
                                                autocomplete="off"
                                                class="mb-4"
                                                value="{{ old('password') }}"/>
                                
                                                <!-- Confirm New Password -->
                                                <label for="password_confirmation" class="text-slate-500/80 font-medium">Re-enter New Password</label>
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
                                                    class="btn-submit w-40"
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
                                            <div class="flex items-center gap-x-2 mb-5 text-slate-600">
                                                <x-ionicon-log-in class="h-5 fill-rose-500" />
                                                <p>Are you sure you want to logout?</p>
                                            </div>
                                            <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                                <button type="button" @click="open = false" class="btn w-32">No</button>
                                                <form id="auth-logout-form" action="{{ route('auth.logout') }}" method="POST" class="w-36">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                    type="submit"
                                                    x-on:click="submitting=true; document.getElementById('auth-logout-form').submit();"
                                                    class="btn-delete w-32">
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

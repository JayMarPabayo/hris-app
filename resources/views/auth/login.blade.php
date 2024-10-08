<x-layout>
    <div class="flex justify-stretch gap-x-2">
        <div class="w-1/2">
            {{-- <h1>Cafe Leone Modern Restaurant</h1>
            <h3>Ramon Chavez Street, Cagayan de Oro City</h3> --}}
            <div class="px-2 py-10 bg-white rounded-md shadow-md flex justify-center items-center">
                <img src="{{ asset('/assets/restaurant.jpg') }}" class="w-96" alt="restaurant-logo">
            </div>
        </div>
        <form class="w-1/2 my-auto px-12" action="{{ route('auth.login') }}" method="POST">
            @csrf
            <h3 class="text-4xl text-center font-semibold mb-8">Sign in</h3>

            <div class="flex items-center gap-x-3 mb-5">
                <x-carbon-user-avatar-filled class="w-10"/>
                <input type="text" name="username"
                placeholder="Username"
                value="{{ old('username') }}"
                @class(['border-red-400' => $errors->has('username')])/>
            </div>

            <div class="flex items-center gap-x-3 mb-5">
                <x-carbon-password class="w-10"/>
                <input type="password" name="password"
                placeholder="Password"
                autocomplete="off"
                value="{{ old('password') }}"
                @class(['border-red-400' => $errors->has('password')])/>
            </div>

            <div class="flex justify-start items-center gap-x-2 w-fit mb-5">
                <input type="checkbox" name="remember" class="rounded-sm border border-slate-400 cursor-pointer">
                <label for="remember" class="whitespace-nowrap text-sm">Remember me?</label>
            </div>

            <button type="submit" class="btn w-full text-lg">
                Log in
            </button>
        </form>
    </div>
</x-layout>
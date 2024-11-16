<x-layout>
    <div class="flex justify-stretch gap-x-2">
        <div class="w-1/2">
            <div class="px-2 py-10 mt-5 bg-white rounded-md shadow-md flex justify-center items-center">
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

            <div class="flex items-center justify-start gap-x-2 w-full mb-5 px-2 py-1">
                <input type="checkbox" name="remember" class="cursor-pointer w-fit" style="margin: 0;">
                <label for="remember" class="whitespace-nowrap text-sm">Remember me?</label>

                <a href="{{ route('password.request') }}" class="ms-auto text-sky-700 hover:underline">Forgot Password?</a>
            </div>

            <button type="submit" class="btn w-full text-lg">
                Log in
            </button>
        </form>
    </div>
</x-layout>
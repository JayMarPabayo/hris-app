<x-layout>
    <div class="flex justify-between gap-x-2 mt-14 w-[60rem] mx-auto bg-white/70 backdrop-blur-sm border border-white p-5 rounded-md">
        <img src="{{ asset('/assets/transparent-logo.png') }}" class="w-96" alt="restaurant-logo">
        <form class="w-[30rem] my-auto px-12" action="{{ route('auth.login') }}" method="POST">
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
                <label for="remember" class="whitespace-nowrap text-sm text-white">Remember me?</label>

                <a href="{{ route('password.request') }}" class="ms-auto text-sky-700 hover:underline">Forgot Password?</a>
            </div>

            <button type="submit" class="btn w-full text-lg">
                Log in
            </button>
        </form>
    </div>

    <section class="text-white mx-auto text-center mt-5 text-sm">
        <p>Cafe Leone Modern Restaurant | Human Resource Information System</p>
        <p>Copyright &copy; 2024 . All Rights Reserved</p>
    </section>
</x-layout>
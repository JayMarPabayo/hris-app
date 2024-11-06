<x-layout>
    <div class="w-1/2 rounded-md bg-white shadow-md p-5 mb-3 mx-auto">
        <h1 class="text-sky-900 text-lg">Reset your Password</h1>

        <form action="{{ route('password.update') }}" method="post">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email --}}
            <div class="mb-4">
                <label for="email">Email</label>
                <input type="text" name="email" value="{{ old('email') }}" class="w-full">
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password">Password</label>
                <input type="password" name="password" class="w-full">
            </div>

            {{-- Confirm Password --}}
            <div class="mb-8">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full">
            </div>


            {{-- Submit Button --}}
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</x-layout>
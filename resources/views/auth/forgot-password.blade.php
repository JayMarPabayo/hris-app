<x-layout>
    <div class="w-1/2 rounded-md bg-white shadow-md p-5 mx-auto">
        <div class="flex items-center justify-between mb-3">
            <h1 class="text-sky-900 text-lg">Reset Password</h1>
        </div>       
    
        <form action="{{ route('password.email') }}" method="post">
            @csrf

            <div class="mb-4">
                <input type="text" name="email" placeholder="Enter email address" value="{{ old('email') }}"
                    class="w-full">
            </div>

            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
</x-layout>
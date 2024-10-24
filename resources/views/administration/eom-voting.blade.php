<x-layout>
    <h3 class="text-base font-semibold mb-3">Administration</h3>
    <div class="flex gap-x-2">
        @include('layouts.navbar')
        <main class="grow">
            <form action="{{ route('eom-voting.updateVoting') }}" method="POST" class="ps-3 mb-5" id="eomVotingForm">
                @csrf
                @method("PUT")
                <div class="flex items-end gap-x-3">
                    <div class="flex items-center gap-x-4">
                        <h1>EOM Voting Open</h1>
                        <label for="eomVoting" class="bg-gray-100 cursor-pointer relative inline-block w-14 h-6 rounded-full">
                            <input type="hidden" name="eomVoting" value="0">
                            <input type="checkbox" name="eomVoting" id="eomVoting" class="sr-only peer" value="1" onchange="document.getElementById('eomVotingForm').submit();" {{ $config->eomVoting ? 'checked' : '' }}>
                            <span class="w-5 h-5 bg-emerald-300 absolute rounded-full left-1 top-0.5 peer-checked:bg-emerald-600 peer-checked:left-8 transition-all duration-500"></span>
                        </label>
                    </div>
                </div>
            </form>
        </main>
    </div>
</x-layout>

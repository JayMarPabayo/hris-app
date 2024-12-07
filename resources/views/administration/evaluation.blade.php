<x-layout>
    <h3 class="text-base font-semibold mb-3 text-white">Administration</h3>
    <div class="flex gap-x-2">
        @include('layouts.navbar')
        <main class="grow">
            <form action="{{ route('evaluation.update') }}" method="POST" class="ps-3 mb-5" id="evaluationForm">
                @csrf
                @method("PUT")
                <div class="flex items-end gap-x-3">
                    <div class="flex items-center gap-x-4">
                        <h1 class="text-white">Open Monthly Evaluation</h1>
                        <label for="evaluation" class="bg-gray-100 cursor-pointer relative inline-block w-14 h-6 rounded-full">
                            <input type="hidden" name="evaluation" value="0">
                            <input type="checkbox" name="evaluation" id="evaluation" class="sr-only peer" value="1" onchange="document.getElementById('evaluationForm').submit();" {{ $config->evaluation ? 'checked' : '' }}>
                            <span class="w-5 h-5 bg-slate-300 absolute rounded-full left-1 top-0.5 peer-checked:bg-emerald-300 peer-checked:left-8 transition-all duration-500"></span>
                        </label>
                    </div>
                </div>
            </form>
        </main>
    </div>
</x-layout>

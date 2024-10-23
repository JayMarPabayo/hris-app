<x-layout>
    <div class="flex gap-x-2 justify-between items-center mb-5">
        <h3 class="text-base font-semibold">Evaluations</h3>
        <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-calendar-heat-map class="h-5" />
            <a href="{{ route('employee-of-the-month.index') }}" class="border-none bg-none underline">
                Voting Results
            </a>
        </div>
    </div>
</x-layout>
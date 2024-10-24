<x-layout>
    <div class="flex gap-x-2 justify-between items-center mb-5">
        <h3 class="text-base font-semibold">Yearly Records</h3>
        <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-calendar-heat-map class="h-5" />
            <a href="{{ route('employee-of-the-month.index') }}" class="border-none bg-none underline">
                Voting Results
            </a>
        </div>
    </div>


    <form method="GET" action="{{ route('employee-of-the-month.monthly') }}" class="flex items-center gap-2 mb-4">
        <input type="number" name="year" value="{{ request('year') ?? $currentYear }}" class="w-48">
        <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">Search<span class="text-lg leading-3">⌕</span></button>
    </form>

    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr class="bg-slate-300">
                <th class="px-4 py-2 text-left">Month</th>
                <th class="px-4 py-2">Employee of the Month</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($yearlyEOM as $monthYear => $employee)
                <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} border-b">
                    <td class="px-4 py-2">
                        {{ date('F', mktime(0, 0, 0, (int)substr($monthYear, 5), 1)) }}
                    </td>
                    <td class="max-w-32 px-4 py-2">
                        @if ($employee)
                            <div class="text-sm font-medium">{{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}</div>
                            <span class="block text-teal-700">{{ $employee->department->name }}</span>
                            <span class="block text-slate-500/70 truncate">{{ $employee->designation }}</span>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="py-20 text-center">
                        <x-empty-alert />
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
</x-layout>
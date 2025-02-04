<x-layout>
    <div class="flex gap-x-2 justify-between items-center mb-5">
        <h3 class="text-base font-semibold text-white">Yearly Records</h3>
        <div class="flex gap-x-2 items-center text-white hover:text-teal-600 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-calendar-heat-map class="h-5" />
            <a href="{{ route('evaluations.index') }}" class="border-none bg-none underline">
                Evaluations
            </a>
        </div>
    </div>


    <form method="GET" action="{{ route('evaluations.monthly') }}" class="flex w-64 items-center gap-2 mb-4" id="yearFilterForm">
        <div class="text-teal-500 text-sm w-36">Select Year</div>
        <select 
            name="year"
            onchange="document.getElementById('yearFilterForm').submit()">
            <option value="" disabled>Select a Year</option>
            @foreach ($availableYears as $availableYear)
                <option value="{{ $availableYear }}" {{ request('year') == $availableYear ? 'selected' : '' }}>
                    {{ $availableYear }}
                </option>
            @endforeach
        </select>
    </form>
    

    <div class="flex gap-x-4">
        <div class="w-1/2">
            <table class="divide-y divide-gray-200">
                <thead>
                    <tr class="bg-slate-300">
                        <th class="px-4 py-2 text-left">Month</th>
                        <th class="px-4 py-2">Employee of the Month</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($yearlyEOM as $monthYear => $employee)
                        <tr class="{{ $loop->even ? 'bg-slate-300/60' : 'bg-slate-200/60' }} border-b">
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
        </div>
    </div>
    
</x-layout>
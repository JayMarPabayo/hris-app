<x-layout>
    <div class="flex gap-x-2 items-center justify-end mb-5 hover:text-teal-700 hover:tracking-wide active:tracking-normal duration-300">
        <h3 class="text-base font-semibold me-auto">Evaluations</h3>
        <x-carbon-calendar class="h-5" />
        <a href="{{ route('evaluations.monthly') }}" class="border-none bg-none underline">
            Yearly Records
        </a>
    </div>

    <form method="GET" action="{{ route('evaluations.index') }}" class="flex items-center gap-2 mb-4" id="monthFilterForm">
        <div class="text-teal-700 text-sm">Select Month</div>
        <input 
            type="month" 
            name="month" 
            value="{{ request('month') ?? $currentMonth }}" 
            class="w-48 btn" 
            onchange="document.getElementById('monthFilterForm').submit()">
        {{-- <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">Filter</button> --}}
    </form>

    <div class="space-y-8">
        @foreach ($employeesByDepartment as $departmentName => $employees)
            <section>
                <h2 class="text-lg font-bold mb-4 text-white bg-slate-700/90 ps-2">{{ $departmentName }}</h2>

                <div class="flex gap-x-4 items-start">
                    <div class="w-1/2">
                        <h3 class="text-base font-semibold mb-2">Employee of the Month</h3>
                        <table>
                            <thead>
                                <tr class="bg-slate-300">
                                    <th>Employee</th>
                                    <th class="text-center">Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                    <tr class="data-row">
                                        <td class="max-w-32">
                                            <div class="text-sm font-medium">{{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}</div>
                                            <span class="block text-slate-500/70 truncate">
                                                {{ $employee->designation }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-lg font-medium">{{ number_format($employee->avg_rating, 2) }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="py-20">
                                            <x-empty-alert />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endforeach
    </div>
</x-layout>
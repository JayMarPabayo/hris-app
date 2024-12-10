<x-layout>
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-lg font-semibold mb-3 text-white">Evaluations</h3>
        <div class="flex gap-x-2 items-center text-white hover:text-teal-600 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-event-schedule class="h-5" />
            <a href="{{ route('evaluations.monthly') }}" class="border-none bg-none underline">
                Yearly Records
            </a>
        </div>
    </div>


    <form method="GET" action="{{ route('evaluations.index') }}" class="flex items-center gap-2 mb-4" id="monthFilterForm">
        <div class="text-teal-600 text-sm">Select Month</div>
        <input 
            type="month" 
            name="month" 
            value="{{ request('month') ?? $currentMonth }}" 
            max="{{ date('Y-m') }}" 
            class="w-48 ms-2" 
            onchange="document.getElementById('monthFilterForm').submit()">
        {{-- <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">Filter</button> --}}
    </form>

    <div class="space-y-8">
        @foreach ($employeesByDepartment as $departmentName => $employees)
            <section>
                <h2 class="text-sm py-2 font-medium mb-4 text-white bg-slate-700/90 ps-2">{{ $departmentName }}</h2>

                <div class="flex gap-x-4 items-start">
                    <div class="w-1/2">
                        <h3 class="text-base font-semibold mb-2 text-white">Employee of the Month</h3>
                        <table>
                            <thead>
                                <tr class="bg-slate-300">
                                    <th>Employee</th>
                                    <th>No. of Entries</th>
                                    <th class="text-center">Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                    <tr class="data-row">
                                        <td class="">
                                            <div class="flex gap-x-2 items-end">
                                                <img src="{{ asset('storage/' . $employee->picture) }}" alt="Employee Picture" class="w-10 h-10 object-cover rounded-md opacity-90 border border-teal-600">
                                                <div class="w-56">
                                                    <div class="text-sm font-medium">{{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}</div>
                                                    <span class="block text-teal-500 truncate">
                                                        {{ $employee->designation }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-x-2">
                                                <x-ionicon-person class="h-4 fill-teal-400/70" />
                                                <p class="text-lg font-normal">{{ $employee->evaluation_count }}</p>
                                                <p class="text-slate-100/70 text-base">/ {{ $employees->count() }}</p>
                                            </div>
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
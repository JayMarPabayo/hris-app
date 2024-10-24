<x-layout>
    <div class="flex gap-x-2 justify-between items-center mb-5">
        <h3 class="text-base font-semibold">Votes</h3>
        <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-calendar class="h-5" />
            <a href="{{ route('employee-of-the-month.monthly') }}" class="border-none bg-none underline">
                Yearly Records
            </a>
        </div>
        
    </div>

    <form method="GET" action="{{ route('employee-of-the-month.index') }}" class="flex items-center gap-2 mb-4">
        <input type="search" placeholder="Search..." name="search" value="{{ request('search') }}" class="flex-grow">
        <input type="hidden" name="sort" value="{{ request('sort') === 'desc' ? 'asc' : 'desc' }}">
        <input type="month" name="month" value="{{ request('month') ?? $currentMonth }}" class="w-48 btn">
        <button type="submit" class="btn w-32 flex justify-center gap-1 items-center">Search<span class="text-lg leading-3">⌕</span></button>
    </form>

        <table>
            <thead>
                <tr class="bg-slate-300">
                    <th>Employee</th>
                    <th class="text-center">Total Votes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $index => $employee)
                    <tr class="data-row">
                        <td class="max-w-32">
                            <div class="text-sm font-medium">{{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}</div>
                            <span class="block text-teal-700">
                                {{ $employee->department->name }}
                            </span>
                            <span class="block text-slate-500/70 truncate">
                                {{ $employee->designation }}
                            </span>
                        </td>
                        <td class="text-center">
                            <p class="text-lg font-medium">{{ $employee->total_votes }}</p>
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
    @if ($employees->count())
        <div class="text-xs mt-4">
            {{ $employees->links()}}
        </div>
    @endif
</x-layout>
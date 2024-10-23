<x-layout>
    <div class="flex gap-x-2 justify-between items-center mb-5">
        <h3 class="text-base font-semibold">Votes</h3>
        <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-calendar class="h-5" />
            <a href="{{ route('employee-of-the-month.monthly') }}" class="border-none bg-none underline">
                Monthly Results
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
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($votes as $index => $vote)
                    <tr class="data-row">
                        <td>{{ $vote->employee_id }}</td>
                        <td class="max-w-32">
                            <div class="text-sm font-medium">{{ "{$vote->employee->lastname}, {$vote->employee->firstname} " . strtoupper(substr($vote->employee->middlename, 0, 1)) . "." }}</div>
                            <span class="block text-teal-700">
                                {{ $vote->employee->department->name }}
                            </span>
                            <span class="block text-slate-500/70 truncate">
                                {{ $vote->employee->designation }}
                            </span>
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
    @if ($votes->count())
        <div class="text-xs mt-4">
            {{ $votes->links()}}
        </div>
    @endif
</x-layout>
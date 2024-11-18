<div>
    <table>
        <thead>
            <tr class="bg-slate-300">
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $index => $employee)
                <tr class="data-row">
                    <td>{{ $employee->id }}</td>
                    <td>{{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}</td>
                    <td>{{ $employee->department->name }}</td>
                    <td>{{ $employee->designation }}</td>
                    <td class="flex justify-center items-center">
                        <a
                            @switch($mode)
                                @case('individual')
                                    href="{{ route('reports.export', ['employee' => $employee->id]) }}"
                                    @break
                                @case('records')
                                    href="{{ route('reports.records', ['employee' => $employee->id]) }}"
                                    @break
                                @default
                                    href="#"
                            @endswitch
                            class="btn mx-auto"
                            target="_blank"
                        >
                            <x-carbon-printer class="w-4" />
                        </a>
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
</div>
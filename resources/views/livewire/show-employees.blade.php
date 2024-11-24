<div>
    <div class="flex items-center gap-x-2 w-full mb-3">
        <select class="w-60" wire:model.live="department" wire:change="getEmployeesByDepartment">
            <option value="" hidden disabled selected>Select Department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>

        @if ($withDesignation)
            <select class="w-60" wire:model="designation" wire:change="getEmployeesByDepartment">
                <option value="">All</option>
                @foreach ($designations as $designation)
                    <option value="{{ $designation }}">{{ $designation }}</option>
                @endforeach
            </select>
        @endif

        <div wire:loading.delay wire:target="getEmployeesByDepartment">
            <x-carbon-awake class="w-5 text-slate-500 animate-spin" />
        </div>

        @if ($employees)
            <a
                href="{{ route('reports.export', ['department' => $this->department, 'designation' => $this->designation] ) }}"
                class="btn ml-auto"
                target="_blank"
            >
                <x-carbon-printer class="w-4" />
            </a>
        @endif
    </div>

    <table>
        <thead>
            <tr class="bg-slate-300">
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Contact Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $employee)
                <tr class="data-row">
                    <td>{{ $employee->id }}</td>
                    <td>{{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}</td>
                    <td>{{ $employee->department->name }}</td>
                    <td>{{ $employee->designation }}</td>
                    <td>{{ $employee->email ? "⎙ {$employee->email}" : ($employee->mobile ? "✆ {$employee->mobile}" : "") }}</td>
                </tr>
            @empty
                <tr>
                   <td colspan="100%" class="py-20"> <x-empty-alert /></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

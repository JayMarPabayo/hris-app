<x-layout>
    <x-employee-data :employee="$employee" />
    <div class="flex justify-end gap-3 items-center pt-3">
        <a href="{{ route('employees.index') }}" class="btn"><span class="mx-4">Go back</span></a>
    </div>
    
</x-layout>
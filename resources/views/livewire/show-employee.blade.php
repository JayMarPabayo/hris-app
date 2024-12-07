<div>
    <div class="flex gap-x-2 items-center w-full">
        <input type="search" name="employee" placeholder="Employee Search" wire:model="search" wire:loading.attr="disabled" class="w-1/2"/>
        <button type="button" class="btn" wire:loading.attr="disabled" wire:click.prevent="searchEmployee">
            Search
        </button>
        <div wire:loading.delay>
            <x-ionicon-reload-sharp class="w-5 text-slate-500 animate-spin" />
        </div>

        @if (!$employee && !is_null($search) && $search !== '')
            <div wire:loading.remove>
                <x-empty-alert />
            </div>
        @endif
        
        @if ($employee)
            <a
            href="{{ route('reports.export', ['employee' => $this->employee->id] ) }}"
            class="btn ml-auto"
            target="_blank"
            >
                <x-carbon-printer class="w-4" />
            </a>
        @endif
        
    </div>
    @if ($employee)
        <div class="mt-3 px-3 py-5 bg-white/30 shadow rounded h-full">
            <div class="print-header mb-10 hidden z-30">
                <div class="flex items-center gap-x-2">
                    <img src="{{ asset('assets/logo.png') }}" alt="Website Logo" class="h-12 w-12">
                    <h2 class="text-lg font-medium">Human Resource Information <System></System></h2>
                </div>
            </div>
            <x-employee-data :employee="$employee" />
        </div>
    @endif
</div>
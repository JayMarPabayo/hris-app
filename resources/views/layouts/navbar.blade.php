<div class="w-52">
    <nav>
        <a href="{{ route('administration.index') }}"
           class="block py-2 px-4 rounded-sm duration-300
           {{ request()->routeIs('administration.*') ? 'bg-slate-500 text-white' : 'hover:bg-slate-300' }}">
           Departments
        </a>
        <a href="{{ route('shifts.index') }}"
           class="block py-2 px-4 rounded-sm duration-300
           {{ request()->routeIs('shifts.*') ? 'bg-slate-500 text-white' : 'hover:bg-slate-300' }}">
           Shifts
        </a>
    </nav>
</div>
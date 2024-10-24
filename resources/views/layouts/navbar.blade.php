<div class="w-52">
    <nav>
        <a href="{{ route('administration.index') }}"
           class="block py-2 px-4 rounded-sm duration-300
           {{ request()->routeIs('administration.index') ? 'bg-slate-500 text-white' : 'hover:bg-slate-300' }}">
           Departments
        </a>
        <a href="{{ route('shifts.index') }}"
           class="block py-2 px-4 rounded-sm duration-300
           {{ request()->routeIs('shifts.index') ? 'bg-slate-500 text-white' : 'hover:bg-slate-300' }}">
           Shifts
        </a>
        <a href="{{ route('administration.leave-request.index') }}"
        class="block py-2 px-4 rounded-sm duration-300
        {{ request()->routeIs('administration.leave-request.index') ? 'bg-slate-500 text-white' : 'hover:bg-slate-300' }}">
        Leave Request
      </a>
      <a href="{{ route('administration.eom-voting') }}"
        class="block py-2 px-4 rounded-sm duration-300
        {{ request()->routeIs('administration.eom-voting') ? 'bg-slate-500 text-white' : 'hover:bg-slate-300' }}">
        EOM Voting
      </a>
    </nav>
</div>
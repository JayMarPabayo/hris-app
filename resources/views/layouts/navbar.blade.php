<div class="w-52">
    <nav class="text-teal-500">
        <a href="{{ route('administration.index') }}"
          class="block py-2 px-4 rounded-sm duration-300
          {{ request()->routeIs('administration.index') ? 'bg-teal-600 text-white' : 'hover:bg-teal-500/30' }}">
          Departments
        </a>
        <a href="{{ route('shifts.index') }}"
          class="block py-2 px-4 rounded-sm duration-300
          {{ request()->routeIs('shifts.index') ? 'bg-teal-600 text-white' : 'hover:bg-teal-500/30' }}">
          Shifts
        </a>
        <a href="{{ route('administration.leave-request.index') }}"
          class="block py-2 px-4 rounded-sm duration-300
          {{ request()->routeIs('administration.leave-request.index') ? 'bg-teal-600 text-white' : 'hover:bg-teal-500/30' }}">
          Leave Request
        </a>
        <a href="{{ route('administration.evaluation') }}"
          class="block py-2 px-4 rounded-sm duration-300
          {{ request()->routeIs('administration.evaluation') ? 'bg-teal-600 text-white' : 'hover:bg-teal-500/30' }}">
          Monthly Evaluation
        </a>
          <a href="{{ route('administration.users') }}"
          class="block py-2 px-4 rounded-sm duration-300
          {{ request()->routeIs('administration.users') ? 'bg-teal-600 text-white' : 'hover:bg-teal-500/30' }}">
          Admins
        </a>
    </nav>
</div>
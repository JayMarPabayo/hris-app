<x-layout>
    <h3 class="text-base font-semibold mb-3 text-white">Reports</h3>

    <div x-data="{ openTab: 'individual' }" class="flex">
        <!-- Menu -->
        <div class="w-52">
            <nav>
                <a href="#" @click.prevent="openTab = 'individual'" 
                   :class="{'bg-teal-600 text-white': openTab === 'individual', 'text-teal-500': openTab !== 'individual'}"
                   class="block py-2 px-4 rounded-sm hover:bg-teal-500/30 duration-300">
                   Individual
                </a>
                <a href="#" @click.prevent="openTab = 'records'" 
                   :class="{'bg-teal-600 text-white': openTab === 'records', 'text-teal-500': openTab !== 'records'}"
                   class="block py-2 px-4 rounded-sm hover:bg-teal-500/30 duration-300">
                   Employee Records
                </a>
                {{-- <a href="#" @click.prevent="openTab = 'department'" 
                   :class="{'bg-teal-600 text-white': openTab === 'department', 'text-teal-500': openTab !== 'department'}"
                   class="block py-2 px-4 rounded-sm hover:bg-teal-500/30 duration-300">
                   Department
                </a> --}}
                <a href="#" @click.prevent="openTab = 'designation'" 
                   :class="{'bg-teal-600 text-white': openTab === 'designation', 'text-teal-500': openTab !== 'designation'}"
                   class="block py-2 px-4 rounded-sm hover:bg-teal-500/30 duration-300">
                   Department & Designation
                </a>
                {{-- <a href="#" @click.prevent="openTab = 'schedules'" 
                   :class="{'bg-teal-600 text-white': openTab === 'schedules', 'text-teal-500': openTab !== 'schedules'}"
                   class="block py-2 px-4 rounded-sm hover:bg-teal-500/30 duration-300">
                   Schedules
                </a> --}}
            </nav>
        </div>
        
        <!-- Content -->
        <div class="flex-1 px-2">
            <div x-cloak x-show="openTab === 'individual'" class="px-4">
                <x-employees-list :employees="$employees ?? []" :mode="'individual'" />
            </div>
            <div x-cloak x-show="openTab === 'records'" class="px-4">
                <x-employees-list :employees="$employees ?? []" :mode="'records'" />
            </div>
            {{-- <div x-cloak x-show="openTab === 'department'" class="p-4 bg-white/30 shadow rounded h-full">
                <!-- Department Table Container -->
                @livewire('show-employees')
            </div> --}}
            <div x-cloak x-show="openTab === 'designation'" class="p-4 bg-white/30 shadow rounded h-full">
                <!-- Department & Designation Table Container -->
                @livewire('show-employees', ['withDesignation' => true])
            </div>
            {{-- <div x-cloak x-show="openTab === 'schedules'" class="p-4 bg-white/30 shadow rounded h-full">
                <!-- Schedules Table Container -->
                @livewire('show-shifts')
            </div> --}}
        </div>
    </div>

</x-layout>

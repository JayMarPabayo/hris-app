<x-layout>
    <div class="flex justify-between">
        <h3 class="text-lg font-semibold mb-3">Swap Schedule Requests</h3>
        <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-event-schedule class="h-5" />
            <a href="{{ route('schedules.index') }}" class="border-none bg-none underline">
                Schedules
            </a>
        </div>
    </div>

    <table class="mt-4 mb-10">
        <thead>
            <tr class="bg-slate-300">
                <th class="text-left">Employee</th>
                <th class="text-left">Current Schedule</th>
                <th class="text-left">Request for</th>
                <th class="text-left">Schedule</th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @php    
                function getOrdinalSuffix($number) {
                    $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
                    if (($number % 100) >= 11 && ($number % 100) <= 13) {
                        return $number . 'th';
                    }
                    return $number . $ends[$number % 10];
                }
            @endphp
            @forelse ($swapRequests as $index => $request)

                @php
                    $lastname = $request->employee->lastname ?? '';
                    $firstname = $request->employee->firstname ?? '';
                    $middlename = $request->employee->middlename ? strtoupper(substr($request->employee->middlename, 0, 1)) : '';

                    $weekString = $request->week;
                    $date = new DateTime();
                    $date->setISODate(substr($weekString, 0, 4), substr($weekString, 6));

                    $month = $date->format('F'); 
                    $year = $date->format('Y');

                    $weekOfMonth = ceil($date->format('j') / 7); 

                    $formattedWeek = getOrdinalSuffix($weekOfMonth);
                @endphp

                <tr class="data-row">
                    <td class="align-middle">{{ "{$lastname}, {$firstname} {$middlename}." }}</td>
                    <td class="flex flex-col gap-y-1">
                        <div class="flex gap-x-2 items-center">
                            <div>
                                <span class="font-semibold">{{ $month }} {{ $year }}</span> <span class="font-medium text-slate-500">
                                    {{ $formattedWeek }} Week
                                </span>
                            </div>
                            <div class="font-bold">
                                •
                            </div>
                            <span class="font-medium rounded-sm text-teal-700/80">
                                {{($request->status === "approved") ? $request->getCoworkerSchedule()?->shift?->name : $request->getSchedule()?->shift?->name }}
                            </span>
                        </div>
                        <div class="flex gap-x-2 justify-start items-center">
                            @foreach (($request->status === "approved") ? $request->getCoworkerSchedule()?->shift->weekdays : $request->getSchedule()?->shift->weekdays as $day)
                                @if (!in_array($day, ($request->status === "approved") ? $request->getCoworkerSchedule()?->dayoffs : $request->getSchedule()?->dayoffs))
                                    <div class="time-style bg-white" style="margin-inline: 0; color: darkgreen">
                                        {{ strtoupper(substr($day, 0, 3)) }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td class="align-middle">
                        {{ $request->getCoworkerFullname() }}
                    </td>
                    <td class="flex flex-col gap-y-1">
                        <div class="flex gap-x-2 items-center">
                            <div>
                                <span class="font-semibold">{{ $month }} {{ $year }}</span> <span class="font-medium text-slate-500">
                                    {{ $formattedWeek }} Week
                                </span>
                            </div>
                            <div class="font-bold">
                                •
                            </div>
                            <span class="font-medium rounded-sm text-teal-700/80">
                                {{ ($request->status === "approved") ? $request->getSchedule()?->shift?->name : $request->getCoworkerSchedule()?->shift?->name }}
                            </span>
                        </div>
                        <div class="flex gap-x-2 justify-start items-center">
                            @foreach (($request->status === "approved") ? $request->getSchedule()?->shift->weekdays : $request->getCoworkerSchedule()?->shift->weekdays as $day)
                            @if (!in_array($day, ($request->status === "approved") ? $request->getSchedule()?->dayoffs : $request->getCoworkerSchedule()?->dayoffs))
                            <div class="time-style bg-white" style="margin-inline: 0; color: darkgreen">
                                {{ strtoupper(substr($day, 0, 3)) }}
                            </div>
                        @endif
                            @endforeach
                        </div>
                    </td>
                    @php
                    $bgColor = '';
                        switch($request->status) {
                            case 'pending':
                                $bgColor = 'bg-yellow-500';
                                break;
                            case 'approved':
                                $bgColor = 'bg-green-500';
                                break;
                            case 'rejected':
                                $bgColor = 'bg-red-500';
                                break;
                            default:
                                $bgColor = 'bg-gray-500';
                                break;
                        }
                    @endphp
                    <td class="text-center align-middle">
                        <span class="px-2 py-1 rounded text-white {{ $bgColor }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    @if ($request->status !== "rejected" && $request->status !== "approved")
                        <td class="flex items-center justify-center gap-x-2">
                            <div x-data="{ open: false }">
                                <button @click.prevent="open = true" title="Reject" class="btn">
                                    <x-carbon-close-filled class="w-4 mx-auto"/>
                                </button>
                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                    <div class="bg-white pt-4 px-4 pb-3 rounded-lg w-96">
                                        <p class="mb-4 text-lg text-rose-700/80">Rejection Confirmation</p>
                                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                            <button type="button" @click="open = false" class="btn">No</button>
                                            <form
                                            id="reject-request-form-{{ $index }}"
                                            action="{{ route('schedules.swap.reject', $request) }}"
                                            method="POST"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                type="submit"
                                                class="btn"
                                                x-on:click="submitting=true; document.getElementById('reject-request-form-{{ $index }}').submit();"
                                                >Yes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-data="{ open: false }">
                                <button @click.prevent="open = true" title="Approve" class="btn">
                                    <x-carbon-checkmark-filled class="w-4 mx-auto fill-emerald-500"/>
                                </button>
                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                    <div class="bg-white pt-4 px-4 pb-3 rounded-lg w-96">
                                        <p class="mb-4 text-lg text-emerald-700/80">Approval Confirmation</p>
                                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                            <button type="button" @click="open = false" class="btn">No</button>
                                            <form
                                            id="approve-request-form-{{ $index }}"
                                            action="{{ route('schedules.swap.approved', $request) }}"
                                            method="POST"
                                            >
                                                @csrf
                                                @method('PUT')
                                                <button
                                                type="submit"
                                                class="btn"
                                                x-on:click="submitting=true; document.getElementById('approve-request-form-{{ $index }}').submit();"
                                                >Yes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    @endif
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
</x-layout>
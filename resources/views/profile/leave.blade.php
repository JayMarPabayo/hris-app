<x-layout>
    <div class="flex gap-x-2 justify-between items-center mb-5">
        <h3 class="text-base font-semibold">Application for Leave of Abscence</h3>
        <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-user-profile class="h-5" />
            <a href="{{ route('profile.index') }}" class="border-none bg-none underline">
                Profile
            </a>
        </div>
    </div>

    <div class="flex items-center rounded-md px-3 py-1 text-base font-semibold bg-slate-700 w-fit text-white gap-x-3  mb-5">
        <p>Leave Credits : </p>
        <p class="text-lg">{{ $remainingCredits ?? 'N/A' }}</p>
    </div>

    @if ($remainingCredits)

    @php
        $nextWeekStart = date('Y-m-d', strtotime('next Monday'));
    @endphp
    <form method="POST" action="{{ route('profile.leave') }}" class="mb-5">
        @csrf
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <div class="flex items-start gap-x-4 mb-4">
            <div class="flex flex-col gap-y-1 mb-2">
                <label for="date">Start</label>
                <input
                    type="date"
                    name="start"
                    value="{{ old('start') }}"
                    class="w-56"
                    @class(['border-red-400' => $errors->has('start')])
                    min="{{ $nextWeekStart }}" />
            </div>
            <div class="flex flex-col gap-y-1 mb-2">
                <label for="date">End</label>
                <input
                    type="date"
                    name="end"
                    value="{{ old('end') }}"
                    class="w-56"
                    @class(['border-red-400' => $errors->has('end')]) 
                    min="{{ $nextWeekStart }}" />
            </div>
            <div class="flex flex-col gap-y-1 mb-2">
                <label for="reason">Reason</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="option-label">
                        <input type="radio" name="reason" class="w-fit focus:outline-none cursor-pointer" value="Vacation Leave" {{ old('reason') == 'Vacation Leave' ? 'checked' : '' }}>
                        <span>Vacation Leave</span>
                    </label>
                    <label class="option-label">
                        <input type="radio" name="reason" class="w-fit focus:outline-none cursor-pointer" value="Sick Leave" {{ old('reason') == 'Sick Leave' ? 'checked' : '' }}>
                        <span>Sick Leave</span>
                    </label>
                    <label class="option-label">
                        <input type="radio" name="reason" class="w-fit focus:outline-none cursor-pointer" value="Leave with Pay" {{ old('reason') == 'Leave with Pay' ? 'checked' : '' }}>
                        <span>Leave with Pay</span>
                    </label>
                    <label class="option-label">
                        <input type="radio" name="reason" class="w-fit focus:outline-none cursor-pointer" value="Maternity Leave" {{ old('reason') == 'Maternity Leave' ? 'checked' : '' }}>
                        <span>Maternity Leave</span>
                    </label>
                    <label class="option-label">
                        <input type="radio" name="reason" class="w-fit focus:outline-none cursor-pointer" value="Paternity Leave" {{ old('reason') == 'Paternity Leave' ? 'checked' : '' }}>
                        <span>Paternity Leave</span>
                    </label>

                    <label class="option-label">
                        <input type="radio" name="reason" class="w-fit focus:outline-none cursor-pointer" value="Others" {{ old('reason') == 'Others' ? 'checked' : '' }} id="other-reason-radio">
                        <span>Others</span>
                    </label>
                </div>
                <input
                     type="text"
                     name="custom_reason"
                     id="custom-reason-input"
                     value="{{ old('custom_reason') }}"
                     class="w-full mt-2 hidden border border-gray-300 rounded"
                     placeholder="Please specify your reason" />
            </div>
        </div>
        <button type="submit" class="btn w-40">Submit</button>
    </form>      
    @endif
    

    <hr class="border-t border-slate-600/30">

    <h3 class="text-base font-semibold my-5">Leave Records</h3>

    <table class="mt-4 mb-10">
        <thead>
            <tr class="bg-slate-300">
                <th class="text-left">Applied at</th>
                <th class="text-left">Reason</th>
                <th class="text-left">Date of Leave</th>
                <th class="text-left">Until</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaveRequests as $index => $request)

                @php
                    $appliedTime = new DateTime($request->created_at);
                @endphp

                <tr class="data-row">
                    <td class="align-top max-w-20">{{ $appliedTime->format('F j, Y ⌚ g:i A') }}</td>
                    <td class="align-top max-w-36 flex flex-col gap-y-1">
                        <span>{{ $request->reason }}</span>
                        @if ($request->custom_reason)
                            <p class="font-normal text-slate-500" style="word-wrap: break-word; word-break: break-word; white-space: normal;">{{ $request->custom_reason }}</p>
                        @endif
                    </td>
                    <td class="align-top">{{ \Carbon\Carbon::parse($request->start)->format('d F Y') }}</td>
                    <td class="align-top">{{ \Carbon\Carbon::parse($request->end)->format('d F Y') }}</td>
                    
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
                    <td class="text-center align-top">
                        <span class="px-2 py-1 rounded text-white {{ $bgColor }}">
                            {{ ucfirst($request->status) }}
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
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const otherReasonRadio = document.getElementById('other-reason-radio');
        const customReasonInput = document.getElementById('custom-reason-input');

        // Check if 'Others' was selected before validation (to show input field)
        if (otherReasonRadio.checked) {
            customReasonInput.classList.remove('hidden');
        }

        // Add event listener for showing/hiding custom reason input
        document.querySelectorAll('input[name="reason"]').forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'Others') {
                    customReasonInput.classList.remove('hidden');
                } else {
                    customReasonInput.classList.add('hidden');
                }
            });
        });
    });
</script>
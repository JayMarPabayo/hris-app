<x-layout>
    <h3 class="text-base font-semibold mb-5">Leave Requests</h3>
    <table class="mt-4 mb-10">
        <thead>
            <tr class="bg-slate-300">
                <th class="text-left">Employee</th>
                <th class="text-left">Applied at</th>
                <th class="text-left">Reason</th>
                <th class="text-left">Date of Leave</th>
                <th class="text-left">Until</th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaveRequests as $index => $request)

                @php
                    $appliedTime = new DateTime($request->created_at);
                @endphp

                <tr class="data-row">
                    <td class="align-top">{{ "{$request->user->employee->lastname}, {$request->user->employee->firstname} " . strtoupper(substr($request->user->employee->middlename, 0, 1)) . "." }}</td>
                    <td class="align-top">{{ $appliedTime->format('F j, Y ⌚ g:i A') }}</td>
                    <td class="align-top flex flex-col">
                        <span>{{ $request->reason }}</span>
                        @if ($request->custom_reason)
                            <p class="font-normal text-slate-500" style="word-wrap: break-word; word-break: break-word; white-space: normal;">{{ $request->custom_reason }}</p>
                        @endif
                    </td>
                    <td class="align-top">{{ $request->start }}</td>
                    <td class="align-top">{{ $request->end }}</td>
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
                    <td class="flex items-center justify-center gap-x-2">
                        @if ($request->status !== "rejected")
                            <div x-data="{ open: false }">
                                <button @click.prevent="open = true" title="Reject" class="btn">
                                    <x-carbon-close-filled class="w-4 mx-auto"/>
                                </button>
                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                    <div class="bg-white pt-4 px-4 pb-3 rounded-lg w-96">
                                        <p class="mb-4">Rejection Confirmation</p>
                                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                            <button type="button" @click="open = false" class="btn">No</button>
                                            <form
                                            id="reject-request-form-{{ $index }}"
                                            action="{{ route('requests.destroy', $request) }}"
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
                                        <p class="mb-4">Approval Confirmation</p>
                                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                            <button type="button" @click="open = false" class="btn">No</button>
                                            <form
                                            id="approve-request-form-{{ $index }}"
                                            action="{{ route('requests.destroy', $request) }}"
                                            method="POST"
                                            >
                                                @csrf
                                                @method('DELETE')
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
                        @endif
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
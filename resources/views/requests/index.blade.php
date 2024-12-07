<x-layout>
    <h3 class="text-base font-semibold mb-5 text-white">Leave Requests</h3>
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
                    $lastname = $request->user->employee->lastname ?? '';
                    $firstname = $request->user->employee->firstname ?? '';
                    $middlename = $request->user->employee->middlename ? strtoupper(substr($request->user->employee->middlename, 0, 1)) : '';
                @endphp

                <tr class="data-row">
                    <td class="align-top">{{ "{$lastname}, {$firstname} {$middlename}." }}</td>
                    <td class="align-top">{{ $appliedTime->format('F j, Y ⌚ g:i A') }}</td>
                    <td class="align-top">
                        <p>{{ $request->reason }}</p>
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
                                $bgColor = 'bg-yellow-600';
                                break;
                            case 'approved':
                                $bgColor = 'bg-emerald-600';
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
                        @if ($request->status !== "rejected" && $request->status !== "approved")
                            <div x-data="{ open: false }">
                                <button @click.prevent="open = true" title="Reject" class="btn">
                                    <x-carbon-close-filled class="w-4 mx-auto"/>
                                </button>
                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-20">
                                    <div class="bg-white pt-4 px-4 pb-3 rounded-lg w-96">
                                        <div class="flex items-center gap-x-2 mb-5 text-slate-600">
                                            <x-ionicon-backspace class="h-5 fill-rose-500" />
                                            <p>Reject Confirmation</p>
                                        </div>
                                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                            <button type="button" @click="open = false" class="btn w-32">No</button>
                                            <form
                                            id="reject-request-form-{{ $index }}"
                                            action="{{ route('requests.destroy', $request) }}"
                                            method="POST"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                type="submit"
                                                class="btn-delete w-32"
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
                                        <div class="flex items-center gap-x-2 mb-5 text-slate-600">
                                            <x-ionicon-checkmark-circle class="h-5 fill-teal-600" />
                                            <p>Approval Confirmation</p>
                                        </div>
                                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                                            <button type="button" @click="open = false" class="btn w-32">No</button>
                                            <form
                                            id="approve-request-form-{{ $index }}"
                                            action="{{ route('requests.update', $request) }}"
                                            method="POST"
                                            >
                                                @csrf
                                                @method('PUT')
                                                <button
                                                type="submit"
                                                class="btn-submit w-32"
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
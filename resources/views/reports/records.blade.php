<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Human Resource Information System</title>
        <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}">
        @vite('resources/css/app.css')
        @vite('resources/css/app-print.css')
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> --}}
    </head> 
<body>
    <div class="h-fit min-h-screen pb-4 bg-black/50">
        <header class="bg-slate-700/60 text-white py-4 px-56 font-normal tracking-wide">
            {{-- @if ($employee)
                <button title="Download as PDF" onclick="convertToPdf('{{ $employee->id . ':' . $employee->lastname . '-' . $employee->firstname }}')" class="btn-add bg-rose-500 text-white shadow-md hover:bg-rose-600">
                    <x-carbon-generate-pdf class="w-6" />
                </button>
            @else
                <button title="Download as PDF" onclick="convertToPdf('{{ $department->name }}')" class="btn-add bg-rose-500 text-white shadow-md hover:bg-rose-600">
                    <x-carbon-generate-pdf class="w-6" />
                </button>
            @endif --}}
            <button title="Print data" onclick="printMainContent()" class="btn-add bg-slate-500 text-white shadow-md hover:bg-slate-600">
                <x-carbon-printer class="w-6" />
            </button>
        </header>
        <main id="printable-area" class="bg-white/90">
            <div class="flex items-center gap-x-2 mb-10">
                <img src="{{ asset('assets/logo.png') }}" alt="Website Logo" class="h-14 w-h-14">
                <div>
                    <h1 class="text-lg font-medium">Human Resource Information System</h1>
                    <p class="text-sm font-medium text-slate-700">
                        Café Leone Modern Restaurant
                    </p>
                    <p class="text-xs text-slate-600">
                        Ramon Chavez Street, Cagayan de Oro City
                    </p>
                </div>
            </div>
            <div class="flex items-end gap-x-2 mb-5">
                <img src="{{ asset('storage/' . $employee->picture) }}" alt="Employee Picture" class="w-24 h-24 object-cover rounded-md opacity-90 border border-teal-600">
                <div class="flex flex-col">
                    <h3 class="text-lg font-semibold">{{ "{$employee->lastname}, {$employee->firstname} " . strtoupper(substr($employee->middlename, 0, 1)) . "." }}</h3>
                    <div class="flex gap-x-2">
                        <h3 class="text-sm font-medium text-teal-800">{{ $employee->department->name }}</h3>
                        <h3 class="text-sm font-medium text-pink-800/70">{{ $employee->designation }}</h3>
                    </div>
                </div>
            </div>

            @if ($leaveRequests->count())
                <h3 class="text-sm mb-2">Leave Requests</h3>
                <hr class="border-t border-slate-500/30 mb-4">

                <table class="mt-4 mb-10 text-xs">
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
            
                            <tr class="data-row text-slate-800">
                                <td class="align-top max-w-36">{{ $appliedTime->format('F j, Y ⌚ g:i A') }}</td>
                                <td class="align-top max-w-56 flex flex-col gap-y-1">
                                    <span>{{ $request->reason }}</span>
                                    @if ($request->custom_reason)
                                        <p class="font-normal text-slate-500" style="word-wrap: break-word; word-break: break-word; white-space: normal;">{{ $request->custom_reason }}</p>
                                    @endif
                                </td>
                                <td class="align-top">{{ \Carbon\Carbon::parse($request->start)->format('d F Y') }}</td>
                                <td class="align-top">{{ \Carbon\Carbon::parse($request->end)->format('d F Y') }}</td>
                                
                                @php
                                    $textColor = '';
                                    switch($request->status) {
                                        case 'pending':
                                            $textColor = 'text-yellow-500';
                                            break;
                                        case 'approved':
                                            $textColor = 'text-green-500';
                                            break;
                                        case 'rejected':
                                            $textColor = 'text-red-500';
                                            break;
                                        default:
                                            $textColor = 'text-gray-500';
                                            break;
                                    }
                                @endphp
                                <td class="text-center align-top">
                                    <span class="font-semi bold tracking-wide {{ $textColor }}">
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
            @endif

            @if ($schedules->count())
                <h3 class="text-sm mb-2">Schedules</h3>
                <hr class="border-t border-slate-500/30 mb-4">

                @php    
                    function getOrdinalSuffix($number) {
                        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
                        if (($number % 100) >= 11 && ($number % 100) <= 13) {
                            return $number . 'th';
                        }
                        return $number . $ends[$number % 10];
                    }
                @endphp
                @forelse ($schedules as $schedule)
                    @php
                        $weekString = $schedule->week;
            
                        $date = new DateTime();
                        $date->setISODate(substr($weekString, 0, 4), substr($weekString, 6));
            
                        $month = $date->format('F'); 
                        $year = $date->format('Y');
            
                        $weekOfMonth = ceil($date->format('j') / 7); 
            
                        $formattedWeek = getOrdinalSuffix($weekOfMonth);
                    @endphp
            
            
                    <div class="mb-4 flex text-sm items-center gap-x-2">
                        <x-carbon-calendar-heat-map class="w-5 text-teal-700"/>
                        <div><span class="font-semibold">{{ $month }} {{ $year }}</span> <span class="font-medium text-slate-500">{{ $formattedWeek }} Week</span></div>
                        <div class="font-bold">
                            •
                        </div>
                        <span class="font-medium rounded-sm text-teal-700/80">
                            {{ $schedule->shift->name }}
                        </span>
                    </div>
            
                    <table class="mt-4 mb-10 text-xs">
                        <thead>
                            <tr class="bg-slate-300">
                                @foreach ($weekdays as $day)
                                    @php
                                        $date = new DateTime();
                                        $date->setISODate(substr($schedule->week, 0, 4), substr($schedule->week, 6)); 
                                        $date->modify("+" . (array_search($day, $weekdays)) . " days"); 
                                        $actualDate = $date->format('Y-m-d');
                                    @endphp
                                    <th class="text-center">
                                        <a href="{{ route('reports.records', array_merge(request()->query(), ['day' => $day])) }}" class="hover:tracking-wider active:tracking-tighter duration-300">
                                            {{ $day }} <br>
                                            <span class="text-[0.7rem] text-slate-500">{{ $actualDate }}</span>
                                        </a>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $startTime = new DateTime($schedule->shift->start_time);
                                $endTime = new DateTime($schedule->shift->end_time);
                            @endphp
            
                            <tr class="data-row">
                                @foreach ($weekdays as $day)
                                    <td class="text-center">
                                    @php
                                        $date = new DateTime();
                                        $date->setISODate(substr($schedule->week, 0, 4), substr($schedule->week, 6)); 
                                        $date->modify("+" . (array_search($day, $weekdays)) . " days"); 
                                        $actualDate = $date->format('Y-m-d');
                                    @endphp
                                    @if (in_array($actualDate, $employee->leaveRequestDates()->toArray()))
                                        <span class="time-style bg-neutral-500 px-5" style="margin-inline: 0">
                                            Leave
                                        </span>
                                    @else
                                        @if (in_array($day, $schedule->shift->weekdays))
                                            @php
                                                $customTime = collect($schedule->customTimes)->firstWhere('day', $day);
                                                
                                                if ($customTime) {
                                                    $startTime = new DateTime($customTime['start_time']);
                                                    $endTime = new DateTime($customTime['end_time']);
                                                } else {
                                                    $startTime = new DateTime($schedule->shift->start_time);
                                                    $endTime = new DateTime($schedule->shift->end_time);
                                                }
                                            @endphp
                                        
                                            <p class="mb-1 text-slate-700/70">{{ $schedule->shift->name }}</p>
                                            @if (in_array($day, $schedule->dayoffs ?? []))
                                                <span class="time-style bg-neutral-500 px-5" style="margin-inline: 0">
                                                    Dayoff
                                                </span>
                                            @else
                                                <span class="time-style bg-teal-700/70" style="margin-inline: 0">
                                                    {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                                                </span>
                                            @endif
                                        @endif
                                    @endif
            
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                @empty
                        <div class="mb-4 flex text-sm items-center gap-x-2">
                            <x-carbon-calendar-heat-map class="w-5 text-teal-700"/>
                            <span class="font-medium rounded-sm text-teal-700/80">
                                No Schedule Yet
                            </span>
                        </div>
                @endforelse
                @endif
        </main>
    </div>
    <script>
        function printMainContent() {
            window.print();
        }
    
        // async function convertToPdf(employeename) {
        //     const { jsPDF } = window.jspdf;
        //     const doc = new jsPDF({ unit: 'in', format: 'legal' });
        //     const canvas = await html2canvas(document.getElementById('printable-area'));
        //     const imgData = canvas.toDataURL('image/png');
            
        //     const pdfWidth = doc.internal.pageSize.getWidth();
        //     const pdfHeight = doc.internal.pageSize.getHeight();
        //     const imgProps = doc.getImageProperties(imgData);
        //     const imgHeight = (imgProps.height * pdfWidth) / imgProps.width;
    
        //     // Consolidate paddingTop and paddingBottom logic
        //     const paddingTop = parseInt(document.getElementById('printable-area').style.paddingTop, 10) || 0;
        //     const paddingBottom = 0; // Adjust this value if necessary
        //     const availableHeight = pdfHeight - paddingTop - paddingBottom;
    
        //     // Ensure image fits within the available height
        //     const finalHeight = imgHeight > availableHeight ? availableHeight : imgHeight;
        //     const yPosition = pdfHeight - finalHeight - 2; // Adjust Y position as needed
            
        //     doc.addImage(imgData, 'PNG', 0, yPosition, pdfWidth, finalHeight);
        //     doc.save(`HRIS-${employeename}.pdf`);
        // }
    </script>
    
</body>
</html>


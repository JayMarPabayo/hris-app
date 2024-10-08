<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Human Resource Information System</title>
        <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}">
        @vite('resources/css/app.css')
        @vite('resources/css/app-print.css')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    </head> 
<body>
    <header class="bg-slate-700/60 text-white py-4 px-56 font-normal tracking-wide">
        @if ($shift)
            <button title="Download as PDF" onclick="convertToPdf('{{ $shift->name }}')" class="btn-add bg-rose-500 text-white shadow-md hover:bg-rose-600">
                <x-carbon-generate-pdf class="w-6" />
            </button>
        @else
            <button title="Download as PDF" onclick="convertToPdf('Schedules')" class="btn-add bg-rose-500 text-white shadow-md hover:bg-rose-600">
                <x-carbon-generate-pdf class="w-6" />
            </button>
        @endif
        <button title="Print data" onclick="printMainContent()" class="btn-add bg-slate-500 text-white shadow-md hover:bg-slate-600">
            <x-carbon-printer class="w-6" />
        </button>
    </header>
    <main id="printable-area">
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
        
        @if ($schedules)
            <table class="text-xs">
                <thead>
                    <tr class="bg-slate-300">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Schedule</th>
                        <th class="text-center">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)

                    @php
                        $startTime = new DateTime($schedule->shift->start_time);
                        $endTime = new DateTime($schedule->shift->end_time);
                    @endphp

                    <tr class="data-row">
                        <td>{{ $schedule->employee->id }}</td>
                        <td>{{ "{$schedule->employee->lastname}, {$schedule->employee->firstname} " . strtoupper(substr($schedule->employee->middlename, 0, 1)) . "." }}</td>
                        <td class="flex gap-x-2 justify-start items-center">
                            @foreach ($schedule->shift->weekdays as $day)
                                <div class="time-style bg-white" style="margin-inline: 0; color: darkgreen">
                                    {{ strtoupper(substr($day, 0, 3)) }}
                                </div>
                            @endforeach
                        </td>
                        <td class="text-center">
                            <span class="time-style" style="margin-inline: 0; color: darkgreen">
                                {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="py-20"> <x-empty-alert /></td>
                    </tr>
            @endforelse
                </tbody>
            </table>
        @endif
    </main>

    <script>
        function printMainContent() {
            window.print();
        }

        async function convertToPdf(filename) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ unit: 'in', format: 'legal' });
            const canvas = await html2canvas(document.getElementById('printable-area'));
            const imgData = canvas.toDataURL('image/png');
            
            const pdfWidth = doc.internal.pageSize.getWidth();
            const pdfHeight = doc.internal.pageSize.getHeight();
            const imgProps = doc.getImageProperties(imgData);
            const imgHeight = (imgProps.height * pdfWidth) / imgProps.width;

            const paddingBottom = 0;
            const availableHeight = pdfHeight - paddingBottom;
            const finalHeight = imgHeight > availableHeight ? availableHeight : imgHeight;

            doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, finalHeight);
            doc.save(`HRIS-${filename}.pdf`);
        }
    </script>
</body>
</html>


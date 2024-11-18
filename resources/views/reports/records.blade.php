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
    <div class="h-fit min-h-screen pb-4 bg-black/50">
        <header class="bg-slate-700/60 text-white py-4 px-56 font-normal tracking-wide">
            @if ($employee)
                <button title="Download as PDF" onclick="convertToPdf('{{ $employee->id . ':' . $employee->lastname . '-' . $employee->firstname }}')" class="btn-add bg-rose-500 text-white shadow-md hover:bg-rose-600">
                    <x-carbon-generate-pdf class="w-6" />
                </button>
            @else
                <button title="Download as PDF" onclick="convertToPdf('{{ $department->name }}')" class="btn-add bg-rose-500 text-white shadow-md hover:bg-rose-600">
                    <x-carbon-generate-pdf class="w-6" />
                </button>
            @endif
            <button title="Print data" onclick="printMainContent()" class="btn-add bg-slate-500 text-white shadow-md hover:bg-slate-600">
                <x-carbon-printer class="w-6" />
            </button>
        </header>
        <main id="printable-area" class="bg-white/90">
            <div class="flex items-center gap-x-2 mb-10 ">
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
            <div class="flex items-end gap-x-2 mb-10">
                <img src="{{ asset('storage/' . $employee->picture) }}" alt="Employee Picture" class="w-24 h-24 object-cover rounded-md opacity-90 border border-teal-600">
                <div class="flex flex-col">
                    <h3 class="text-lg font-semibold">{{ auth()->user()->name }}</h3>
                    <div class="flex gap-x-2">
                        <h3 class="text-sm font-medium text-teal-800">{{ $employee->department->name }}</h3>
                        <h3 class="text-sm font-medium text-pink-800/70">{{ $employee->designation }}</h3>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        function printMainContent() {
            window.print();
        }

        async function convertToPdf(employeename) {
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
            doc.save(`HRIS-${employeename}.pdf`);
        }
    </script>
</body>
</html>


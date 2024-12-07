<div>
    <h3 class="text-base font-semibold text-cyan-500 mb-3">Personal Information</h3>

    <div class="mb-5">
        <img src="{{ asset('storage/' . $employee->picture) }}" alt="Employee Picture" class="w-24 h-24 object-cover rounded-md opacity-90 border border-teal-600">
    </div>

    <div class="grid grid-cols-11 gap-x-1 gap-y-1 px-2 mb-5 items-center text-xs">
        {{-- 1st Row --}}
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Name
        </div>
        <div class="col-span-4 text-white font-semibold">
            {{ $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname . ($employee->nameextension ? ' ' . $employee->nameextension : '') }}
        </div>
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Telephone No.
        </div>
        <div class="col-span-3 text-white font-semibold">
          {{ $employee->telephone ?? "N/A"}}
        </div>
        {{-- 2nd Row --}}
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Gender
        </div>
        <div class="col-span-4 text-white font-semibold">
          {{ $employee->gender ?? "N/A"}}
        </div>
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Mobile No.
        </div>
        <div class="col-span-3 text-white font-semibold">
            {{ $employee->mobile ?? "N/A"}}
        </div>
        {{-- 3rd Row --}}
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Birthdate
        </div>
        <div class="col-span-4 text-white font-semibold">
            {{ $employee->birthdate ?? "N/A"}}
        </div>
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Email
        </div>
        <div class="col-span-3 text-white font-semibold">
            {{ $employee->email ?? "N/A"}}
        </div>
        {{-- 4th Row --}}
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Place of Birth
        </div>
        <div class="col-span-4 text-white font-semibold">
            {{ $employee->birthplace ?? "N/A"}}
        </div>
        {{-- <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          GSIS No.
        </div>
        <div class="col-span-3 text-white font-semibold">
            {{ $employee->gsis ?? "N/A"}}
        </div> --}}

        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Civil Status
        </div>
        <div class="col-span-3 text-white font-semibold">
            {{ $employee->civilstatus ?? "N/A"}}
        </div>
               {{-- 5th Row --}}
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Pag-ibig No.
        </div>
        <div class="col-span-4 text-white font-semibold">
            {{ $employee->pagibig ?? "N/A"}}
        </div>
        {{-- 6th Row --}}
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Citizenship
        </div>
        <div class="col-span-3 text-white font-semibold">
            {{ $employee->citizenship ?? "N/A"}}
        </div>
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          PhilHealth No.
        </div>
        <div class="col-span-4 text-white font-semibold">
            {{ $employee->philhealth ?? "N/A"}}
        </div>
        {{-- 7th Row --}}
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Blood Type
        </div>
        <div class="col-span-3 text-white font-semibold">
            {{ $employee->bloodtype ?? "N/A"}}
        </div>
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          SSS No.
        </div>
        <div class="col-span-4 text-white font-semibold">
            {{ $employee->sss ?? "N/A"}}
        </div>
        {{-- 8th Row --}}
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Height (m)
        </div>
        <div class="col-span-3 text-white font-semibold">
            {{ $employee->height ?? "N/A"}}
        </div>
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          TIN No.
        </div>
        <div class="col-span-4 text-white font-semibold">
            {{ $employee->tin ?? "N/A"}}
        </div>
        {{-- 9th Row --}}
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Weight (kg)
        </div>
        <div class="col-span-3 text-white font-semibold">
            {{ number_format($employee->weight, 2) ?? "N/A"}}
        </div>
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
          Agency Employee No.
        </div>
        <div class="col-span-4 text-white font-semibold">
            {{ $employee->agencynumber ?? "N/A"}}
        </div>
    </div>
    
    <h3 class="text-base font-semibold text-cyan-500 mb-3">Family Background</h3>
    
    <div class="grid grid-cols-11 gap-x-1 gap-y-1 px-2 mb-5 items-start text-xs">
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
            Father
        </div>
        <div class="col-span-4 text-white font-semibold">
            {{ $employee->father_firstname . ' ' . $employee->father_middlename . ' ' . $employee->father_lastname . ($employee->father_nameextension ? ' ' . $employee->father_nameextension : '') }}
        </div>
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
            {{ $employee->spouse_firstname ? 'Spouse' : 'Mother' }}
        </div>
        <div class="col-span-3 text-white font-semibold">
            @if ($employee->spouse_firstname)
                {{ $employee->spouse_firstname . ' ' . $employee->spouse_middlename . ' ' . $employee->spouse_lastname . ($employee->spouse_nameextension ? ' ' . $employee->spouse_nameextension : '') }}
            @else
                {{ $employee->mother_firstname . ' ' . $employee->mother_middlename . ' ' . $employee->mother_lastname . ($employee->mother_nameextension ? ' ' . $employee->mother_nameextension : '') }}
            @endif
        </div>
        @if ($employee->spouse_firstname)
            <div class="col-span-2 text-slate-400 tracking-wider text-xs">
                Mother
            </div>
            <div class="col-span-4 text-white font-semibold">
                {{ $employee->mother_firstname . ' ' . $employee->mother_middlename . ' ' . $employee->mother_lastname . ($employee->mother_nameextension ? ' ' . $employee->mother_nameextension : '') }}
            </div>
        @endif
        @if ($employee->spouse_occupation)
            <div class="col-span-2 text-slate-400 tracking-wider text-xs">
                Occupation
            </div>
            <div class="col-span-3 text-white font-semibold">
                {{ $employee->spouse_occupation }}
            </div>
        @endif
        <div class="col-span-2 text-slate-400 tracking-wider text-xs">
            {{ $employee->children->count() > 0 ? 'Children' : '' }}
        </div>
        <div class="col-span-4 row-span-2 text-white font-semibold">
            @foreach ($employee->children as $child)
                <p>
                    {{ $child->fullname }} <span class="font-medium text-xs text-slate-400">({{ $child->gender }})</span>
                </p>
            @endforeach
        </div>
        @if ($employee->spouse_employerbusiness)
            <div class="col-span-2 text-slate-400 tracking-wider text-xs">
                Employer/Business
            </div>
            <div class="col-span-3 text-white font-semibold">
                {{ $employee->spouse_employerbusiness }}
            </div>
        @endif
        @if ($employee->spouse_businessaddress)
            <div class="col-start-7 col-span-2 text-slate-400 tracking-wider text-xs">
                Business Address
            </div>
            <div class="col-span-3 text-white font-semibold">
                {{ $employee->spouse_businessaddress }}
            </div>
        @endif
        @if ($employee->spouse_telephone)
            <div class="col-start-7 col-span-2 text-slate-400 tracking-wider text-xs">
                Telephone
            </div>
            <div class="col-span-3 text-white font-semibold">
                {{ $employee->spouse_telephone }}
            </div>
        @endif
    </div>
    
    @if ($employee->education->isNotEmpty())
        <div class="avoid-break">
            <h3 class="text-base font-semibold text-cyan-500 mb-3">Educational Background</h3>
    
            <table class="table-auto w-full text-sm text-left text-white mb-5">
                <thead class="text-gray-700 uppercase bg-slate-200" style="font-size: 0.65rem;">
                    <tr>
                        <th scope="col" class="py-2 ps-2">Level</th>
                        <th scope="col" class="py-2 px-1">School</th>
                        <th scope="col" class="py-2 px-1">Degree/Course</th>
                        <th scope="col" class="py-2 px-1 text-center">Attendance</th>
                        <th scope="col" class="py-2 px-1">Level/Units</th>
                        <th scope="col-2" class="py-2 px-1 text-center">
                            <p>Graduated</p>
                        </th>
                        <th scope="col" class="py-2 px-1">Awards/Honors</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($employee->education as $index => $row)
                        <tr class="align-top">
                            <td class="py-2 px-1 ps-2">{{ $row->level }}</td>
                            <td class="py-2 px-1">{{ $row->school }}</td>
                            <td class="py-2 px-1">{{ $row->degree }}</td>
                            <td class="py-2 px-1 text-center">{{ $row->start ?? '' }} - {{ $row->end ?? '' }}</td>
                            <td class="py-2 px-1">{{ $row->earned ?? '' }}</td>
                            <td class="py-2 px-1 text-center">{{ $row->graduated }}</td>
                            <td class="py-2 px-1">{{ $row->accolades }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    @endif
    
    
    {{-- @if ($employee->eligibilities->isNotEmpty())
        <div class="avoid-break">
            <h3 class="text-base font-semibold text-cyan-500 mb-3">Government Employment Eligibilities</h3>
    
            <table class="table-auto w-full text-sm text-left text-gray-600 mb-5">
                <thead class="text-gray-700 uppercase bg-slate-200" style="font-size: 0.65rem;">
                    <tr>
                        <th scope="col" class="py-2 ps-2">Examination</th>
                        <th scope="col" class="py-2 px-1">Rating</th>
                        <th scope="col" class="py-2 px-1">Examination Date</th>
                        <th scope="col" class="py-2 px-1">Place of Examination</th>
                        <th scope="col" class="py-2 px-1">License</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($employee->eligibilities as $index => $row)
                        <tr class="align-top">
                            <td class="py-2 px-1 ps-2">{{ $row->examination }}</td>
                            <td class="py-2 px-1">
                                {{ $row->rating ? number_format($row->rating, 2) . ' %' : '' }}
                            </td>
                            <td class="py-2 px-1">{{ $row->examdate }}</td>
                            <td class="py-2 px-1">{{ $row->address }}</td>
                            @if ($row->license)
                                <td class="py-2 px-1">
                                    {{ Str::upper($row->license) }}
                                    @if ($row->validity)
                                        <span class="pl-2 text-slate-400">validity:</span> {{ $row->validity }}
                                    @endif
                                </td>
                            @endif
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif --}}
    
    @if ($employee->workexperiences->isNotEmpty())
        <div class="avoid-break">
            <h3 class="text-base font-semibold text-cyan-500 mb-3">Work Experience</h3>
    
            <table class="table-auto w-full text-sm text-left text-white">
                <thead class="text-gray-700 uppercase bg-slate-200" style="font-size: 0.65rem;">
                    <tr>
                        <th scope="col" class="py-2 ps-2">Inclusive Dates</th>
                        <th scope="col" class="py-2 px-1">Position</th>
                        <th scope="col" class="py-2 px-1">Office/Company</th>
                        <th scope="col" class="py-2 px-1">Monthly Salary</th>
                        <th scope="col" class="py-2 px-1">Job/Pay Grade</th>
                        <th scope="col" class="py-2 px-1">Status</th>
                        {{-- <th scope="col" class="py-2 px-1">Government Service</th> --}}
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($employee->workExperiences as $index => $row)
                        <tr class="align-top">
                            <td class="py-2 px-1 ps-2">{{ $row->start . " - " . $row->end  }}</td>
                            <td class="py-2 px-1">{{ $row->position }}</td>
                            <td class="py-2 px-1">{{ $row->company }}</td>
                            <td class="py-2 px-1">{{ number_format($row->monthlysalary, 2) }}</td>
                            <td class="py-2 px-1">{{ $row->paygrade }}</td>
                            <td class="py-2 px-1">{{ $row->appointmentstatus }}</td>
                            {{-- <td class="py-2 px-1">{{ $row->govtservice ? 'Yes' : 'No' }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
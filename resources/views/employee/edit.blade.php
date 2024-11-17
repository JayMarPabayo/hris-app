<x-layout>
    <form id="update-employee-form" method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="flex justify-between items-center border-b border-slate-300 pb-3 mb-3">
            <h3 class="text-base font-semibold">Edit Employee</h3>
            <section class="flex justify-between items-center gap-2">
                <select name="department_id" class="w-80 @class(['border-red-400' => $errors->has('department_id')])>
                    <option value="" hidden disabled selected>Select Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" @selected($department->id == $employee->department_id)>{{ $department->name }}</option>
                    @endforeach
                </select>
                  <input type="text"
                  name="designation"
                  placeholder="Designation"
                  value="{{ old('designation', $employee->designation) }}"
                  @class(['border-red-400' => $errors->has('designation')])/>
            </section>
        </div>

        <h3 class="text-sm font-normal text-teal-600 mb-3">Personal Information</h3>

        <div class="mb-3">
            <div class="flex items-end gap-2">
                <img id="image-preview" src="{{ asset('storage/' . $employee->picture) }}" alt="Employee Picture" class="w-24 h-24 object-cover rounded-md opacity-90 border border-teal-600">

                <input type="file" name="picture" id="picture" accept="image/*" class="w-52" onchange="previewImage(event)">
            </div>
            @error('picture')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <label for="firstname" class="block">Name</label>
        <div class="flex items-center gap-2 mb-2">

            <input type="text" name="firstname"
            placeholder="First name"
            value="{{ old('firstname', $employee->firstname) }}"
            @class(['border-red-400' => $errors->has('firstname')])/>

            <input type="text" name="middlename"
            placeholder="Middle name"
            value="{{ old('middlename', $employee->middlename) }}"
            @class(['border-red-400' => $errors->has('middlename')])/>

            <input type="text" name="lastname"
            placeholder="Last name"
            value="{{ old('lastname', $employee->lastname) }}"
            @class(['border-red-400' => $errors->has('lastname')])/>

            <select name="nameextension" @class(['border-red-400' => $errors->has('nameextension')])>
                <option value="" hidden disabled selected>Suffix</option>
                @foreach ($suffixes as $suffix)
                    <option value="{{ $suffix }}" @selected($suffix === $employee->nameextension)>{{ $suffix }}</option>
                @endforeach
            </select>

        </div>
        {{-- PERSONAL INFORMATION 1 --}}
        <div class="flex items-center gap-2 mb-2">

            <div class="flex-grow">
                <label for="birthdate">Birthdate</label>
                <input type="date" name="birthdate"
                value="{{ old('birthdate', $employee->birthdate) }}"
                @class(['border-red-400' => $errors->has('birthdate')]) />
            </div>

            <div class="w-1/3">
                <label for="birthplace">Place of Birth</label>
                <input type="text" name="birthplace"
                placeholder="Address"
                value="{{ old('birthplace', $employee->birthplace) }}"
                @class(['border-red-400' => $errors->has('birthplace')])/>
            </div>

            <div class="flex-grow">
                <label for="gender">Gender</label>
                <select name="gender" @class(['border-red-400' => $errors->has('gender')])>
                    <option value="Male" @selected($employee->gender === "Male")>Male</option>
                    <option value="Female" @selected($employee->gender === "Female")>Female</option>
                </select>
            </div>

            <div class="flex-grow">
                <label for="civilstatus">Civil Status</label>
                <select name="civilstatus" @class(['border-red-400' => $errors->has('civilstatus')])>
                    @foreach ($civilstatus as $status)
                        <option value="{{ $status }}" @selected($employee->civilstatus === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-grow">
                <label for="citizenship">Citizenship</label>
                <select name="citizenship" @class(['border-red-400' => $errors->has('citizenship')])>
                    @foreach ($citizenships as $citizenship)
                        <option value="{{ $citizenship }}" @selected($employee->citizenship === $citizenship)>{{ $citizenship }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        {{-- PERSONAL INFORMATION 2 --}}
        <div class="flex items-center gap-2 mb-2">

            <div class="w-32">
                <label for="height">Height (m)</label>
                <input type="number" name="height"
                step=".01"
                placeholder="Meters"
                value="{{ old('height', $employee->height) }}"
                @class(['border-red-400' => $errors->has('height')])/>
            </div>

            <div class="w-32">
                <label for="weight">Weight (kg)</label>
                <input type="number" name="weight"
                step=".01"
                placeholder="Kilograms"
                value="{{ old('weight', $employee->weight) }}"
                @class(['border-red-400' => $errors->has('weight')])/>
            </div>

            <div class="w-32">
                <label for="bloodtype">Blood Type</label>
                <select name="bloodtype" @class(['border-red-400' => $errors->has('bloodtype')])>
                    @foreach ($bloodtypes as $type)
                        <option value="{{ $type }}" @selected($employee->bloodtype === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-grow">
                <label for="telephone">Telephone No.</label>
                <input type="tel" name="telephone"
                value="{{ old('telephone', $employee->telephone) }}"
                 @class(['border-red-400' => $errors->has('telephone')])/>
            </div>

            <div class="flex-grow">
                <label for="mobile">Mobile No.</label>
                <input type="tel" name="mobile"
                value="{{ old('mobile', $employee->mobile) }}"
                @class(['border-red-400' => $errors->has('mobile')])/>
            </div>

            <div class="w-60">
                <label for="email">Email</label>
                <input type="tel" name="email"
                value="{{ old('email', $employee->email) }}"
                @class(['border-red-400' => $errors->has('email')])/>
            </div>

        </div>

        {{-- GOVERNMENT IDs --}}
        <div class="mb-2 bg-slate-300 p-2 rounded-sm">

            <div class="flex items-center gap-2 mb-2">

                {{-- <div class="w-1/3">
                    <label for="gsis">GSIS ID</label>
                    <input type="text" name="gsis"
                    value="{{ old('gsis', $employee->gsis) }}"
                    @class(['border-red-400' => $errors->has('gsis')])/>
                    @error('gsis')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div class="w-1/2">
                    <label for="pagibig">Pag-ibig ID</label>
                    <input type="text" name="pagibig"
                    value="{{ old('pagibig', $employee->pagibig) }}"
                    @class(['border-red-400' => $errors->has('pagibig')])/>
                    @error('pagibig')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-1/2">
                    <label for="philhealth">PhilHealth</label>
                    <input type="text" name="philhealth"
                    value="{{ old('philhealth', $employee->philhealth) }}"
                    @class(['border-red-400' => $errors->has('philhealth')])/>
                </div>

            </div>

            <div class="flex items-center gap-2 mb-2">

                <div class="w-1/3">
                    <label for="sss">SSS No.</label>
                    <input type="text" name="sss"
                    value="{{ old('sss', $employee->sss) }}"
                    @class(['border-red-400' => $errors->has('sss')])/>
                </div>

                <div class="w-1/3">
                    <label for="tin">TIN No.</label>
                    <input type="text" name="tin"
                    value="{{ old('tin', $employee->tin) }}"
                    @class(['border-red-400' => $errors->has('tin')])/>
                </div>

                <div class="w-1/3">
                    <label for="agencynumber">Agency ID/No</label>
                    <input type="text" name="agencynumber"
                    value="{{ old('agencynumber', $employee->agencynumber) }}"
                    @class(['border-red-400' => $errors->has('agencynumber')])/>
                </div>

            </div>
        </div>

        {{-- ADDRESSES --}}
        <div class="grid grid-cols-2 gap-x-10 border-b border-slate-300 pb-5 mb-2">

            <div class="col-span-1">

                <h3 class="text-sm font-normal text-teal-600 mb-3">Residential Address</h3>

                <div class="flex items-center justify-stretch gap-3">

                    <div class="flex flex-col gap-2">
                        
                        <div>
                            <label for="residential_region">Region</label>
                            <input type="text" name="residential_region"
                            value="{{ old('residential_region', $employee->residential_region) }}"
                            @class(['border-red-400' => $errors->has('residential_region')])/>
                        </div>

                        <div>
                            <label for="residential_province">Province</label>
                            <input type="text" name="residential_province"
                            value="{{ old('residential_province', $employee->residential_province) }}"
                            @class(['border-red-400' => $errors->has('residential_province')])/>
                        </div>

                        <div>
                            <label for="residential_city">City/Municipality</label>
                            <input type="text" name="residential_city"
                            value="{{ old('residential_city', $employee->residential_city) }}"
                            @class(['border-red-400' => $errors->has('residential_city')])/>
                        </div>

                        <div>
                            <label for="residential_barangay">Barangay</label>
                            <input type="text" name="residential_barangay"
                            value="{{ old('residential_barangay', $employee->residential_barangay) }}"
                            @class(['border-red-400' => $errors->has('residential_barangay')])/>
                        </div>

                    </div>

                    <div class="flex flex-col gap-2">

                        <div>
                            <label for="residential_zipcode">Zip Code</label>
                            <input type="number" name="residential_zipcode"
                            value="{{ old('residential_zipcode', $employee->residential_zipcode) }}"
                            @class(['border-red-400' => $errors->has('residential_zipcode')])/>
                        </div>

                        <div>
                            <label for="residential_subdivision">Subdivision/Village</label>
                            <input type="text" name="residential_subdivision"
                            value="{{ old('residential_subdivision', $employee->residential_subdivision) }}"
                            @class(['border-red-400' => $errors->has('residential_subdivision')])/>
                        </div>

                        <div>
                            <label for="residential_street">Street</label>
                            <input type="text" name="residential_street"
                            value="{{ old('residential_street', $employee->residential_street) }}"
                            @class(['border-red-400' => $errors->has('residential_street')])/>
                        </div>

                        <div>
                            <label for="residential_houseblock">House/Block/Lot No.</label>
                            <input type="text" name="residential_houseblock"
                            value="{{ old('residential_houseblock', $employee->residential_houseblock) }}"
                            @class(['border-red-400' => $errors->has('residential_houseblock')])/>
                        </div>

                    </div>

                </div>
            </div>

            <div class="col-span-1">
                <h3 class="text-sm font-normal text-teal-600 mb-3">Permanent Address</h3>
                <div class="flex items-center justify-stretch gap-3">
                    <div class="flex flex-col gap-2">
                        <div>
                            <label for="permanent_region">Region</label>
                            <input type="text"
                            name="permanent_region"
                            value="{{ old('permanent_region', $employee->permanent_region) }}"
                            @class(['border-red-400' => $errors->has('permanent_region')])/>
                        </div>
                        <div>
                            <label for="permanent_province">Province</label>
                            <input type="text"
                            name="permanent_province"
                            value="{{ old('permanent_province', $employee->permanent_province) }}"
                            @class(['border-red-400' => $errors->has('permanent_province')])/>
                        </div>
                        <div>
                            <label for="permanent_city">City/Municipality</label>
                            <input type="text"
                            name="permanent_city"
                            value="{{ old('permanent_city', $employee->permanent_city) }}"
                            @class(['border-red-400' => $errors->has('permanent_city')])/>
                        </div>
                        <div>
                            <label for="permanent_barangay">Barangay</label>
                            <input type="text"
                            name="permanent_barangay"
                            value="{{ old('permanent_barangay', $employee->permanent_barangay) }}"
                            @class(['border-red-400' => $errors->has('permanent_barangay')])/>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div>
                            <label for="permanent_zipcode">Zip Code</label>
                            <input type="number"
                            name="permanent_zipcode"
                            value="{{ old('permanent_zipcode', $employee->permanent_zipcode) }}"
                            @class(['border-red-400' => $errors->has('permanent_zipcode')])/>
                        </div>
                        <div>
                            <label for="permanent_subdivision">Subdivision/Village</label>
                            <input type="text"
                            name="permanent_subdivision"
                            value="{{ old('permanent_subdivision', $employee->permanent_subdivision) }}"
                            @class(['border-red-400' => $errors->has('permanent_subdivision')])/>
                        </div>
                        <div>
                            <label for="permanent_street">Street</label>
                            <input type="text"
                            name="permanent_street"
                            value="{{ old('permanent_street', $employee->permanent_street) }}"
                            @class(['border-red-400' => $errors->has('permanent_street')])/>
                        </div>
                        <div>
                            <label for="permanent_houseblock">House/Block/Lot No.</label>
                            <input type="text"
                            name="permanent_houseblock"
                            value="{{ old('permanent_houseblock', $employee->permanent_houseblock) }}"
                            @class(['border-red-400' => $errors->has('permanent_houseblock')])/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FAMILY BACKGROUND --}}
        <h3 class="text-sm font-normal text-teal-600 mb-3">Family Background</h3>

        <label for="father_firstname" class="block">Father Information</label>
        <div class="flex items-center gap-2 mb-2">

            <input type="text" name="father_firstname"
            placeholder="First name"
            value="{{ old('father_firstname', $employee->father_firstname) }}"
            @class(['border-red-400' => $errors->has('father_firstname')])/>

            <input type="text" name="father_middlename"
            placeholder="Middle name"
            value="{{ old('father_middlename', $employee->father_middlename) }}"
            @class(['border-red-400' => $errors->has('father_middlename')])/>

            <input type="text" name="father_lastname"
            placeholder="Last name"
            value="{{ old('father_lastname', $employee->father_lastname) }}"
            @class(['border-red-400' => $errors->has('father_lastname')])/>

            <select name="father_nameextension" @class(['border-red-400' => $errors->has('father_nameextension')])>
                <option value="" hidden disabled selected>Suffix</option>
                @foreach ($suffixes as $suffix)
                    <option value="{{ $suffix }}" @selected($suffix === $employee->father_nameextension)>{{ $suffix }}</option>
                @endforeach
            </select>

        </div>

        <label for="mother_firstname" class="block">Mother Information</label>
        <div class="flex items-center gap-2 mb-2">

            <input type="text" name="mother_firstname"
            placeholder="First name"
            value="{{ old('mother_firstname', $employee->mother_firstname) }}"
            @class(['border-red-400' => $errors->has('mother_firstname')])/>

            <input type="text" name="mother_middlename"
            placeholder="Middle name"
            value="{{ old('mother_middlename', $employee->mother_middlename) }}"
            @class(['border-red-400' => $errors->has('mother_middlename')])/>

            <input type="text" name="mother_lastname"
            placeholder="Last name"
            value="{{ old('mother_lastname', $employee->mother_lastname) }}"
            @class(['border-red-400' => $errors->has('mother_lastname')])/>

            <select name="mother_nameextension" @class(['border-red-400' => $errors->has('mother_nameextension')])>
                <option value="" hidden disabled selected>Suffix</option>
                @foreach ($suffixes as $suffix)
                    <option value="{{ $suffix }}" @selected($suffix === $employee->mother_nameextension)>{{ $suffix }}</option>
                @endforeach
            </select>

        </div>

        <label for="spouse_firstname" class="block">Spouse Information</label>
        <div class="flex items-center gap-2 mb-2">

            <input type="text" name="spouse_firstname"
            placeholder="First name"
            value="{{ old('spouse_firstname', $employee->spouse_firstname) }}"
            @class(['border-red-400' => $errors->has('spouse_firstname')])/>

            <input type="text" name="spouse_middlename"
            placeholder="Middle name"
            value="{{ old('spouse_middlename', $employee->spouse_middlename) }}"
            @class(['border-red-400' => $errors->has('spouse_middlename')])/>

            <input type="text" name="spouse_lastname"
            placeholder="Last name"
            value="{{ old('spouse_lastname', $employee->spouse_lastname) }}"
            @class(['border-red-400' => $errors->has('spouse_lastname')])/>
            
            <select name="spouse_nameextension" @class(['border-red-400' => $errors->has('spouse_nameextension')])>
                <option value="" hidden disabled selected>Suffix</option>
                @foreach ($suffixes as $suffix)
                    <option value="{{ $suffix }}" @selected($suffix === $employee->spouse_nameextension)>{{ $suffix }}</option>
                @endforeach
            </select>

        </div>

        <div class="flex items-center gap-2 mb-4 pb-5 border-b border-slate-300">

            <div class="flex-grow">
                <label for="spouse_occupation">Occupation</label>
                <input type="text" name="spouse_occupation"
                placeholder="Spouse Occupation"
                value="{{ old('spouse_occupation', $employee->spouse_occupation) }}"
                @class(['border-red-400' => $errors->has('permanent_zipcode')])/>
            </div>

            <div class="flex-grow">
                <label for="spouse_employerbusiness">Employer/Business Name</label>
                <input type="text" name="spouse_employerbusiness"
                placeholder="Employer or Business/Company Name"
                value="{{ old('spouse_employerbusiness', $employee->spouse_employerbusiness) }}"
                @class(['border-red-400' => $errors->has('spouse_employerbusiness')])/>
            </div>

            <div class="w-1/3">
                <label for="spouse_businessaddress">Business/Company Address</label>
                <input type="text" name="spouse_businessaddress"
                placeholder="Address"
                value="{{ old('spouse_businessaddress', $employee->spouse_businessaddress) }}"
                @class(['border-red-400' => $errors->has('spouse_businessaddress')])/>
            </div>

            <div class="flex-grow">
                <label for="spouse_telephone">Telephone No.</label>
                <input type="tel" name="spouse_telephone"
                placeholder="Spouse Contact No."
                value="{{ old('spouse_telephone', $employee->spouse_telephone) }}"
                @class(['border-red-400' => $errors->has('spouse_telephone')])/>
            </div>

        </div>

        {{-- CHILDREN --}}
        @livewire('create-children', ['children' =>  old('children', $employee->children->toArray() ?? [])])

        {{-- EDUCATION --}}
        @livewire('create-education', ['educations' => old('educations', $employee->education->toArray() ?? [])])

        {{-- ELIGIBILITIES --}}
        @livewire('create-eligibility', ['eligibilities' => old('eligibilities', $employee->eligibilities->toArray() ?? [])])

        {{-- WORK EXPERIENCES --}}
        @livewire('create-work-experience', ['workexperiences' => old('workexperiences', $employee->workexperiences->toArray() ?? [])])

        <div class="flex justify-end gap-3 items-center pt-3">
            <button type="submit"
            class="btn"
            x-on:click="submitting=true; document.getElementById('update-employee-form').submit();" >
                <span class="mx-4">Submit</span>
            </button>
            <a href="{{ route('employees.index') }}" class="btn"><span class="mx-4">Cancel</span></a>
        </div>

    </form>
    <script>
        function previewImage(event) {
            const preview = document.getElementById('image-preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result; 
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-layout>
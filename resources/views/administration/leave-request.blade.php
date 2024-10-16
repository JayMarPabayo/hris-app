<x-layout>
    <h3 class="text-base font-semibold mb-3">Administration</h3>
    <div class="flex gap-x-2">
        @include('layouts.navbar')
        <main class="grow">
            <form action="{{ route('leave-request.updateMaxCredits') }}" method="POST" class="ps-3 mb-5">
                @csrf
                @method("PUT")
                <div class="flex items-end gap-x-3">
                    <div class="w-1/3">
                        <label for="maxCredits">Maximum No. of Leave Credits</label>
                        <input type="number" name="maxCredits"
                        placeholder="No. of Credits"
                        value="{{ old('maxCredits', $config->maxCredits) }}"
                        @class(['border-red-400' => $errors->has('maxCredits')])/>
                    </div>
                    <button type="submit" class="btn" >Update</button>
                </div>
            </form>
            <form action="{{ route('leave-request.updateMaxDays') }}" method="POST" class="ps-3">
                @csrf
                @method("PUT")
                <div class="flex items-end gap-x-3">
                    <div class="w-1/3">
                        <label for="maxDays">Maximum No. Days for Leave</label>
                        <input type="number" name="maxDays"
                        placeholder="No. of Days"
                        value="{{ old('maxDays', $config->maxDays) }}"
                        @class(['border-red-400' => $errors->has('maxDays')])/>
                    </div>
                    <button type="submit" class="btn" >Update</button>
                </div>
            </form>
        </main>
    </div>
</x-layout>
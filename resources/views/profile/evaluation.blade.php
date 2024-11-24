<x-layout>
    <div class="flex gap-x-2 justify-between items-center mb-5">
        <h3 class="text-base font-semibold">
            {{ $coworkers->isNotEmpty() ? 'Employee Evaluation' : ($evaluatedCoworkers->isNotEmpty() ? 'Evaluated Coworkers' : 'Employee Evaluation') }}
        </h3>
        <div class="flex gap-x-2 items-center hover:text-teal-700 hover:scale-105 active:scale-95 duration-300">
            <x-carbon-user-profile class="h-5" />
            <a href="{{ route('profile.index') }}" class="border-none bg-none underline">
                Profile
            </a>
        </div>
    </div>

    <h3 class="text-2xl text-teal-800 mb-5">{{ $employee->department->name }}</h3>

    @if ($coworkers->isNotEmpty())
        <form
        action="{{ route('profile.evaluation.post') }}"
        method="POST"
        x-data="{
            ratings: {},
            initializeRatings(coworkers) {
                coworkers.forEach(coworker => {
                    this.ratings[coworker] = {};
                });
            },
            updateRating(coworkerId, questionId, value) {
                this.ratings[coworkerId][questionId] = value;
            },
            allRated() {
                return Object.values(this.ratings).every(coworker => 
                    Object.values(coworker).length === {{ $questions->count() }} &&
                    Object.values(coworker).every(rating => rating)
                );
            }
        }"
        x-init="initializeRatings({{ $coworkers->pluck('id') }})" >
            @csrf
            @foreach ($coworkers as $coworker)
                <div class="bg-white/70 rounded-md shadow-md p-3 mb-5">
                    <div class="flex gap-x-4 items-center mb-5">
                        <img src="{{ asset('storage/' . $coworker->picture) }}" alt="Employee Picture" class="w-16 h-16 object-cover rounded-md opacity-90 border border-teal-600">
                        <div class="flex flex-col">
                            <h3 class="text-lg font-semibold">
                                {{ $coworker->firstname . ' ' . $coworker->middlename . ' ' . $coworker->lastname . ($coworker->nameextension ? ' ' . $coworker->nameextension : '') }}
                            </h3>
                            <h4 class="text-sm font-medium text-pink-800/70">{{ $coworker->designation }}</h4>
                        </div>
                    </div>
    
                    <input type="hidden" name="coworkers[]" value="{{ $coworker->id }}">
                    @foreach ($questions as $question)
                        <div class="flex items-center gap-x-2 text-base text-slate-700 font-normal mb-2 pb-1 border-b border-slate-200/20">
                            <input type="hidden" name="questions[{{ $coworker->id }}][]" value="{{ $question->id }}">
                            <div class="font-medium w-5">
                                {{ $question->number }}.
                            </div>
                            <div class="w-[30rem]">
                                {{ $question->question }}
                            </div>
                            <select name="ratings[{{ $coworker->id }}][]" class="w-72 bg-none rating-select" 
                                x-on:change="updateRating({{ $coworker->id }}, {{ $question->id }}, $event.target.value)">
                                <option value="" selected disabled>--- Select Rating ---</option>
                                <option value="1">1 - Strongly Disagree</option>
                                <option value="2">2 - Disagree</option>
                                <option value="3">3 - Neutral</option>
                                <option value="4">4 - Agree</option>
                                <option value="5">5 - Strongly Agree</option>
                            </select>
                            
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit" class="btn w-48 ms-auto" x-bind:disabled="!allRated()" :class="{ 'opacity-50 cursor-not-allowed': !allRated() }">
                    Submit Evaluations
                </button>
            </div>
        </form>
    @endif


    
    {{-- @if ($coworker)
        <form action="{{ route('profile.evaluation.post') }}" method="POST" class="bg-slate-500/20 p-5 mb-10 -mx-5">
            @csrf
            <img src="{{ asset('storage/' . $coworker->picture) }}" alt="Employee Picture" class="w-24 h-24 object-cover rounded-md opacity-90 border border-teal-600">
            <div class="flex flex-col mb-3">
                <h3 class="text-lg font-semibold"> {{ $coworker->firstname . ' ' . $coworker->middlename . ' ' . $coworker->lastname . ($coworker->nameextension ? ' ' . $coworker->nameextension : '') }}</h3>
                <div class="flex gap-x-2">
                    <h3 class="text-sm font-medium text-pink-800/70">{{ $coworker->designation }}</h3>
                </div>
            </div>

            <input type="hidden" name="coworker" value="{{ $coworker->id }}">
            @foreach ($questions as $question)
                <div class="flex items-center gap-x-2 text-base text-slate-700 font-normal mb-2 pb-1 border-b border-slate-200/20">
                    <input type="hidden" name="question[]" value="{{ $question->id }}">
                    <div class="font-medium w-5">
                        {{ $question->number }}.
                    </div>
                    <div class="w-[30rem]">
                        {{ $question->question }}
                    </div>

                    <select name="rating[]" class="w-72 bg-none rating-select">
                        <option value="1">1 - Strongly Disagree</option>
                        <option value="2">2 - Disagree</option>
                        <option value="3">3 - Neutral</option>
                        <option value="4">4 - Agree</option>
                        <option value="5">5 - Strongly Agree</option>
                    </select>
                </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit"  x-on:click="submitting=true" class="btn w-48 ms-auto">Submit</button>
            </div>
        </form>       
    @endif --}}

    @if ($coworkers->isNotEmpty() && $evaluatedCoworkers->isNotEmpty())
        <h3 class="text-base font-semibold mb-5">
            {{ $evaluatedCoworkers->isNotEmpty() ? 'Evaluated Coworkers' : '' }}
        </h3>
    @endif
    @forelse ($evaluatedCoworkers as $coworker)
        <section class="mb-3">
                <div class="flex gap-x-2 items-center">
                    <img src="{{ asset('storage/' . $coworker->picture) }}" alt="Employee Picture" class="w-10 h-10 object-cover rounded-md opacity-90 border border-teal-600">
                    <div class="w-56">
                        <h3 class="text-base font-semibold"> {{ $coworker->firstname . ' ' . $coworker->middlename . ' ' . $coworker->lastname . ($coworker->nameextension ? ' ' . $coworker->nameextension : '') }}</h3>
                        <div class="flex gap-x-2">
                            <h3 class="text-xs font-medium text-pink-800/70">{{ $coworker->designation }}</h3>
                        </div>
                    </div>
                    <div class="text-teal-600 font-normal text-lg">
                        <span>
                            {{ number_format($coworker->avg_rating, 2) }}
                        </span>
                    </div>
                </div>
        </section>
    @empty
        
    @endforelse
</x-layout>
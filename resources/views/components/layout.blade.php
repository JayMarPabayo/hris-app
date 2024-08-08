<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Human Resource Information System</title>
        <script src="//unpkg.com/alpinejs" defer></script>
        <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}">
        @vite('resources/css/app.css')
        @livewireStyles
    </head>
    <body class="h-screen text-slate-700 text-xs" x-data="{ submitting: false }">
            <div class="h-fit min-h-screen pb-4 bg-gradient-to-r from-slate-500/80 from-65%% to-slate-300">
          
            @include('layouts.header')
            <div x-data="{ flash:true }" class="mt-5">
                @if (session()->has('success'))
                    <div x-show="flash" role="alert" class="relative mx-auto max-w-7xl bg-green-400/70 text-green-800 border border-green-800/30 tracking-wide text-sm py-2 px-5 rounded-md shadow-md mb-4">
                        <strong class="block">Success:</strong>
                        {{ session('success') }}
                        <x-carbon-close @click="flash = false" class="w-7 absolute right-3 top-3 cursor-pointer hover:scale-105 active:scale-95" />
                    </div>
                @endif

                @if (session()->has('error'))
                    <div x-show="flash" role="alert" class="relative mx-auto max-w-7xl bg-red-400/70 text-red-800 border border-red-800/30 tracking-wide text-sm py-2 px-5 rounded-md shadow-md mb-4">
                        <strong class="block">Error:</strong>
                        {{ session('error') }}
                        <x-carbon-close @click="flash = false" class="w-7 absolute right-3 top-3 cursor-pointer hover:scale-105 active:scale-95" />
                    </div>
                @endif

                @if ($errors->any())
                    <div x-show="flash" role="alert" class="relative mx-auto max-w-7xl bg-red-400/70 text-red-800 border border-red-800/30 tracking-wide text-sm py-2 px-5 rounded-md shadow-md mb-4">
                        <strong class="block">Errors:</strong>
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <x-carbon-close @click="flash = false" class="w-7 absolute right-3 top-3 cursor-pointer hover:scale-105 active:scale-95" />
                    </div>
                @endif
            </div>

            <div class="bg-slate-200 pt-3 pb-10 px-5 max-w-7xl mx-auto mt-5 rounded-md shadow-md">
                {{ $slot }}
            </div>
            <x-spinner />
         </div>
         @livewireScripts
    </body>
</html>

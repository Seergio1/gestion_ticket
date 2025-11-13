<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- <title>{{ config('app.name', 'Gestion de projet') }}</title> --}}
        <title>Gestion de projet</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/ticket.css') }}" rel="stylesheet">
        <link href="{{ asset('css/ticket-form.css') }}" rel="stylesheet">
        <link href="{{ asset('css/projet-list.css') }}" rel="stylesheet">
        <link href="{{ asset('css/import.css') }}" rel="stylesheet">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            {{-- @include('layouts.navigation') --}}

            <!-- Page Heading -->
            {{-- <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $breadcrumbs  ?? '' }}
                </div>
            </header>  --}}

            <!-- Page Content -->
            <main >
                {{ $slot }}
            </main>
        </div>
        @livewireScripts
        <script src="{{ mix('js/app.js') }}" defer></script>
    </body>
</html>

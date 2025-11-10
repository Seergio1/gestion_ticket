    <div class="min-h-screen bg-gray-100">

        {{-- Navigation si nécessaire --}}
        @include('layouts.navigation')

        {{-- Page Heading (breadcrumbs) --}}
        <header class="bg-white shadow">
            {{-- <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"> --}}
                <div class="w-full py-6 px-4 sm:px-6 lg:px-8">
                {{ $breadcrumbs ?? '' }}
            </div>
        </header>

        {{-- Page Content --}}
        <main>
            {{ $slot }}
        </main>
    </div>


<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include("layouts.header")
<body>
    <div id="app">
        @include("layouts.nav")

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/variousMiscellaneous.js') }}"></script>
    @stack('scripts')

</body>
    @include("layouts.footer")
</html>

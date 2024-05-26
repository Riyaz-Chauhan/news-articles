@include('partials._header')

<body>
    <main class="main-content">
        @include('partials._alert')
        <div class="contents">
            @yield('content')
        </div>
    </main>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr.js') }}"></script>
    @stack('page_js')
</body>

@include('partials._footer')

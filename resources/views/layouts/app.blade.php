<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" href="{{ config('settings.brand.logo') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('uikit/assets/phosphor-regular/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="@yield('body-class', '')">
    @yield('content')

    <div class="portal-toast-stack">
        @foreach (['success', 'warning', 'error'] as $level)
            @if (session($level))
                <div class="portal-toast {{ $level }}">{{ session($level) }}</div>
            @endif
        @endforeach

        @if ($errors->any())
            <div class="portal-toast error">
                <strong>There were validation issues.</strong>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif
    </div>

    <script src="{{ asset('uikit/assets/ui-kit.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    @stack('scripts')
</body>
</html>

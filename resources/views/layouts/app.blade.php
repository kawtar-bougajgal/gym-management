<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <script>
        window.translations = @json(__('messages'));
        window.locale = "{{ app()->getLocale() }}";
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.jsx'])

    {{-- Pages push their own extra CSS here via @push('styles') ... @endpush --}}
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen">
        {{ $slot }}
    </div>
</body>
</html>
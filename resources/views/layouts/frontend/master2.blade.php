<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Website' }}</title>
        <link rel="stylesheet" href="{{asset('build/assets/app-DAvf9Ktg.css')}}" data-navigate-track="reload" />        
    @livewireStyles
</head>
<body>

    <main>
        {{ $slot }}
    </main>

    @livewireScripts
    @yield('scripts')   
</body>
</html>

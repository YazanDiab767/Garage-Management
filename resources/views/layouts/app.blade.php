<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/header.css') }}" />

    <link rel="icon" href="{{ asset('images/icon.png') }}">

    <title>@yield('title')</title>

    @yield('style')
</head>
<body>
    @yield('content')

    {{-- scripts --}}
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/all.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('script')
</body>
</html>
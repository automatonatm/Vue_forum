<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>



    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

    @yield('header')
    <style>
        body {
            padding-bottom: 100px !important;

        }

        [v-cloak]{
            display: none !important;
        }

    </style>

    <script>
        window.App = {!!
                json_encode([
                'csrfToken' => csrf_token(),
                'signedIn' => Auth::check(),
                'user' => Auth::user()
                ])
         !!}
    </script>
</head>
<body>
    <div id="app">
     @include('layouts.nav')

        <main class="py-4">
            @yield('content')
        </main>

        <alert message="{{session('flash')}}"></alert>


    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="{{ asset('js/bootstrap-notify.js') }}" ></script>

</body>
</html>

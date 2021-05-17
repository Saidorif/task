<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Task</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css?version=1.1.1') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css?version=1.1.1') }}">
        <link rel="stylesheet" href="{{ asset('fonts/fonts.css?version=1.1.1') }}">

    </head>
    <body>
        <div id="app">
            <Master/>
        </div>
        <script src="{{ asset('js/feather.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.js') }}"></script>
        <script src="{{ asset('js/app.js?version=1.1.8') }}"></script>
        <script>
            feather.replace()
        </script>

    </body>
</html>

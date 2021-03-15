<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Task</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('css/app.css?version=1.1.1') }}">
        <link rel="stylesheet" href="{{ asset('fonts/fonts.css?version=1.1.1') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css?version=1.1.1') }}">

    </head>
    <body>
        <div id="app">
            <Master/>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>

    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="HOMMS">
        <meta name="author" content="Ericson Paciente">
        <title>HOMMS - {{ $title }}</title>
        <link href="{{ asset('css') }}/bootstrap_5.2.3.min.css" rel="stylesheet">
        <link href="{{ asset('css') }}/fa/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css') }}/timepicker_1.3.5.min.css">
        <link href="{{ asset('css') }}/style.css" rel="stylesheet">
        <link href="{{ asset('css') }}/settings.css" rel="stylesheet">
        <link href="{{ asset('css') }}/datepicker.css" rel="stylesheet">
        <link href="{{ asset('css') }}/modal.css" rel="stylesheet">
        <link href="{{ asset('css') }}/chat.css" rel="stylesheet">
        <link href="{{ asset('css') }}/inputs.css" rel="stylesheet">
        <link href="{{ asset('css') }}/custom_size.css" rel="stylesheet">
        <script src="{{ asset('js') }}/bootstrap_5.2.3.min.js"></script>
        <script src="{{ asset('js') }}/jquery_3.7.1.min.js"></script>
        <script src="{{ asset('js') }}/pusher_8.2.0.min.js"></script>
        <script src="{{ asset('js') }}/jquery_ui_1.13.2.min.js"></script>
        <script src="{{ asset('js') }}/timepicker_1.3.5.min.js"></script>
        <script src="{{ asset('js') }}/highcharts_11.4.0.min.js"></script>
        <script src="{{ asset('js') }}/script.js"></script>
    </head>
    <body>
        @yield('content')
    </body>
</html>
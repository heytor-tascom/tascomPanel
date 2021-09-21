<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('rhp') }}/favicon.ico">

    <title>Hospital Rondon</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="{{ asset('rhp') }}/plugins/fontawesome/css/all.min.css" rel="stylesheet">
    <!-- MedicalFont -->
    <link href="{{ asset('rhp') }}/plugins/webfont-medical-icons/css/wfmi-style.css" rel="stylesheet">
    <!-- AnimateCSS -->
    <link href="{{ asset('rhp') }}/plugins/animatecss/animate.css" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Bootstrap Select -->
    <link href="{{ asset('rhp') }}/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">

    <link href="{{ asset('rhp') }}/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('rhp') }}/css/main.css">
</head>
<body>

    @yield('content')

    <script src="{{ asset('rhp') }}/js/core/jquery.min.js"></script>
    <script src="{{ asset('rhp') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('rhp') }}/js/core/bootstrap-material-design.min.js"></script>
    <script src="{{ asset('rhp') }}/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script src="{{ asset('rhp') }}/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>

    @stack('scripts')
</body>
</html>

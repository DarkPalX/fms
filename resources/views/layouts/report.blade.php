<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PMC | Fuel Monitoring System</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link href="{{ asset('assets/dist/css/google-font-api.css') }}" rel="stylesheet">
    <style>
        body { font-size: 14px; }
    </style>
    @yield('pagecss')
</head>
<body>
    <div class="wrapper">
        <section class="invoice" style="padding: 15px;">
            @yield('content')
        </section>
    </div>

    <!-- <script type="text/javascript"> 
        window.addEventListener("load", window.print());
    </script> -->
    @yield('pagejs')
</body>
</html>
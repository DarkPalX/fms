<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PMC | Fuel Monitoring System</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="{{ asset('assets/dist/css/google-font-api.css') }}" rel="stylesheet">

    <style>
        body { font-size: 14px; }

        div.fadeMe {
            opacity:    0.5; 
            background: #000; 
            width:      100%;
            height:     100%; 
            z-index:    10;
            top:        0; 
            left:       0; 
            position:   fixed; 
        }

    </style>

    @yield('pagecss')

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="fadeMe"></div>
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.header')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.sidebar')
        <!-- Main Sidebar Container -->
        
        <div class="content-wrapper">
            @yield('content')
        </div>
    
        <div class="modal fade" id="modal-overlay">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Change Password</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form class="form-horizontal" role="form" method="post" action="{{ route('change-password') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    When changing a password, please do not use special characters and ZERO (0) number to avoid future error.
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Password <i class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input required type="password" class="form-control" id="pword" placeholder="********">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Confirm Password <i class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input requred type="password" name="pword" class="form-control" id="cpword" placeholder="********">
                                        <span id="messagealert"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" id="changepword">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0
            </div>
            <strong>Copyright &copy; {{ date('Y') }}. All rights reserved. Philsaga Mining Corporation</strong>
        </footer>
    </div>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

<!-- overlayScrollbars -->
<script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('assets/dist/js/demo.js') }}"></script>

<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>

<script>
    $('#changepword').click(function(){
        var pass = $('#pword').val();
        var pass2 = $('#cpword').val();

        if(pass != pass2){
            alert('the passwords didn\'t match!');
            return false;
        } else {
            return true;
        }
    });
</script>

@if($msg = Session::get('successMsg'))
    <script>
        $(document).ready(function(){

            toastr.success("{!! Session::get('successMsg') !!}");
        });
    </script>
@endif

@if($msg = Session::get('errorMsg'))
    <script>
        $(document).ready(function(){

            toastr.error("{!! Session::get('errorMsg') !!}");
        });
    </script>
@endif

@yield('pagejs')

<script type="text/javascript">
    function getXSRF() {
        var name = "XSRF-TOKEN=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }        
   
    $(function() {            
        var checker = getXSRF();          
        if(checker){            
            $('.fadeMe').hide();
        }            
    });
</script>

</body>
</html>

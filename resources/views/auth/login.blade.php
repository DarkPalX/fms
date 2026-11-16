<!DOCTYPE html>
<html lang="en">
<head>
    <title>PMC | Fuel Monitoring System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->  
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/css/main.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/login/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login/vendor/animate/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/login/vendor/animsition/css/animsition.min.css') }}">

<!--===============================================================================================-->
<style>
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
</head>


<body>
    <div class="fadeMe"></div>
    
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form autocomplete="off" method="POST" action="{{ url('/fms/checklogin') }}" class="login100-form validate-form">
                    @csrf
                    <span class="login100-form-title">
                        <img style="height: 150px;width: 350px;" src="{{ asset('assets/login/images/fms.png') }}" alt="">
                    </span>

                    @if($message = Session::get('error'))
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                    </div>
                    @endif

                    @if($message = Session::get('msg'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                    </div>
                    @endif
                    
                    <div class="wrap-input100 rs1-wrap-input100 validate-input m-b-20" data-validate="Type user name">
                        <input id="first-name" class="input100" type="text" name="domainAccount" placeholder="User name">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 rs2-wrap-input100 validate-input m-b-20" data-validate="Type password">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100"></span>
                    </div>
                    
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Sign in
                        </button>
                    </div>
                </form>

                <div class="login100-more" style='background-image: url("{{asset("assets/login/images/logo.jpg")}}");'></div>
            </div>
        </div>
    </div>
    
    

    <div id="dropDownSelect1"></div>
    
<!--===============================================================================================-->
    <script src="{{ asset('assets/login/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('assets/login/vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('assets/login/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('assets/login/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('assets/login/js/main.js') }}"></script>
    
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



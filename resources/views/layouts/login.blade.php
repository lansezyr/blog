<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>xPangZi后台管理系统</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="{{asset('backend/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('backend/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{asset('backend/css/components-md.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('backend/css/plugins-md.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{asset('backend/css/login.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <!-- <link rel="shortcut icon" href="favicon.ico" /> -->
        <link href="{{asset('backend/plugins/particleground/css/style.css')}}" rel="stylesheet" type="text/css" />
        </head>
    <!-- END HEAD -->
    <style>
        body{height:100%;background:#16a085;overflow:hidden;}
        canvas{z-index:-1;position:absolute;}
    </style>
    <body class="login" style="background: #16a085;">
    <!--<div class="menu-toggler sidebar-toggler"></div>  -->
    <!-- END SIDEBAR TOGGLER BUTTON -->
        <!-- BEGIN LOGO
        <div class="logo">
            <a href="/">
                <img src="{{asset('backend/img/logo-big.png')}}" alt="" />
            </a>
        </div>-->
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <!-- count particles-->

        <!-- particles.js container -->
        <div id="particles">
            <div class="content" style="margin-top: 10%;">
                @yield('content')
            </div>
        </div>
        <div class="copyright"> 2017 © IAdmin Dashboard. </div>
        <!--[if lt IE 9]>
            <script src="../assets/global/plugins/respond.min.js"></script>
            <script src="../assets/global/plugins/excanvas.min.js"></script> 
        <![endif]-->
        <script src="{{asset('backend/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('backend/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>

        <!-- scripts -->
        <script src="{{asset('backend/plugins/particleground/jquery.particleground.js')}}"></script>
        <script>
            $(document).ready(function() {
                //粒子背景特效
                $('#particles').particleground({
                    dotColor: '#5cbdaa',
                    lineColor: '#5cbdaa'
                });
            });
        </script>
    </body>

</html>
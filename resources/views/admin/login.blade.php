<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>请登录</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('index') }}/assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('index') }}/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="{{ asset('index') }}/assets/css/style.css" rel="stylesheet">
    <link href="{{ asset('index') }}/assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>

<body>

<!-- **********************************************************************************************************************************************************
MAIN CONTENT
*********************************************************************************************************************************************************** -->

<div id="login-page">
    <div class="container">

        <form class="form-login" action="{{url('login')}}" method="post">

            <h2 class="form-login-heading">sign in now</h2>

            <input type="text" style="display:none" id="_token" name="_token"
                   value="{{ csrf_token() }}">
            <div class="login-wrap">
                <input type="text" class="form-control" placeholder="User ID" autofocus id = 'a_username' name="a_username">
                <br>
                <input type="password" class="form-control" placeholder="Password" id="a_password" name="a_password">
                <label class="checkbox">
		                <span class="pull-right">
		                    <a data-toggle="modal" href="login.html#myModal"> Forgot Password?</a>

		                </span>
                </label>
                <button class="btn btn-theme btn-block" href="index.html" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
                <hr>



            </div>

        </form>

    </div>
</div>
<footer class="site-footer navbar-fixed-bottom">
    <div class="text-center">
        <a href="http://www.beian.miit.gov.cn/" target="_blank"> 粤ICP备19101562号</a>

    </div>
</footer>

<!-- js placed at the end of the document so the pages load faster -->
<script src="{{ asset('index') }}/assets/js/jquery.js"></script>
<script src="{{ asset('index') }}/assets/js/bootstrap.min.js"></script>

<!--BACKSTRETCH-->
<!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
<script type="text/javascript" src="{{ asset('index') }}/assets/js/jquery.backstretch.min.js"></script>
<!--toastr-->
<link href="{{ asset('index') }}/css/toastr.min.css" rel="stylesheet" />
<script src="{{ asset('index') }}/js/toastr.min.js"></script>
<script language="javascript">
    toastr.options.positionClass = 'toast-bottom-right';
</script>
<script>
    $.backstretch("{{ asset('index') }}/assets/img/login-bgg.jpg", {speed: 500});
    console.info({{$errors ->count()}});
    @if($errors ->count())

    console.info("{{implode($errors->all())}}");
    toastr.error("{{implode($errors->all())}}");
    // toastr.();
    @endif
</script>


</body>
</html>

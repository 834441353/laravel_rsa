@extends('home.common')
@section('content')

    <section id="container">
    <!-- **********************************************************************************************************************************************************
MAIN SIDEBAR MENU
*********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <aside>
        <div id="sidebar" class="nav-collapse ">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="nav-accordion">

                <p class="centered"><a href="{{ url('tableshow') }}"><img src="{{ asset('index') }}/assets/img/ui-sam.jpg"
                                                                          class="img-circle" width="60"></a></p>
                <h5 class="centered">YLSJ_AuthorizationSystems</h5>

                <li class="mt">
                    <a href="{{ url('tableshow') }}">
                        <i class="fa fa-th"></i>
                        <span>表</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a class="active" href="javascript:;">
                        <i class="fa  fa-tasks"></i>
                        <span>添加</span>
                    </a>
                    <ul class="sub">
                        <li><a  href="{{url('/tableshow/addCompany')}}">添加公司</a></li>
                        <li><a  href="{{url('/tableshow/addDevice')}}">添加设备</a></li>
                        <li class="active"><a  href="{{url('/tableshow/addDid')}}">添加ID</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>导入</span>
                    </a>
                    <ul class="sub">
                        <li><a  href="morris.html">导入设备列表</a></li>
                        {{--<li><a  href="chartjs.html">导入公司列表</a></li>--}}
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="{{url('/tableshow/searchDevice')}}" >
                        <i class="fa fa-search"></i>
                        <span>设备查询</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a href="{{ url('/showStdevices') }}">
                        <i class="fa fa-th"></i>
                        <span>扫描笔设备表</span>
                    </a>
                </li>
            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->

    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <h3><i class="fa fa-angle-right"></i> 添加设备</h3>

            <!-- BASIC FORM ELELEMNTS -->

            <div class="row mt">
                <div class="col-lg-12">
                    <div class="form-panel">
                        <h4 class="mb"><i class="fa fa-angle-right"></i> 设备信息</h4>
                        <form class="form-horizontal style-form" method="post">

                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">&nbsp&nbsp&nbsp&nbsp&nbsp授权ID</label>
                                <div class="col-sm-10">

                                    <input type="text" class="form-control" name="input1" value="{{$idinfo}}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"></label>
                                <div class="col-sm-10">

                                    <div class="alert alert-success">
                                        <b> 添加成功! </b>
                                        授权ID将在设备烧录ID后第一次联网后自动激活
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div><!-- col-lg-12-->
            </div><!-- /row -->


        </section>
    </section>



@endsection
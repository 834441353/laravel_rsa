@extends('home.common')
@section('content')
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
                <a class="active" href="{{ url('tableshow') }}">
                    <i class="fa fa-th"></i>
                    <span>表</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="javascript:;">
                    <i class="fa  fa-tasks"></i>
                    <span>添加</span>
                </a>
                <ul class="sub">
                    <li><a  href="{{url('/tableshow/addCompany')}}">添加公司</a></li>
                    <li><a  href="{{url('/tableshow/addDevice')}}">添加设备</a></li>
                    <li><a  href="{{url('/tableshow/addDid')}}">添加ID</a></li>
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


@endsection
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

                <p class="centered"><a href="profile.html"><img src="{{ asset('index') }}/assets/img/ui-sam.jpg"
                                                                class="img-circle" width="60"></a></p>
                <h5 class="centered">Marcel Newman</h5>

                <li class="mt">
                    <a href="{{ url('tableshow') }}">
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
                    </ul>
                </li>
                <li class="sub-menu">
                    <a class="active" href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>导入</span>
                    </a>
                    <ul class="sub">
                        <li><a  href="morris.html">导入设备列表</a></li>
                        {{--<li><a  href="chartjs.html">导入公司列表</a></li>--}}
                    </ul>
                </li>
            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->


@endsection
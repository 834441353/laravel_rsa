@extends('home.common')
@section('content')
    <section id="container">
        <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->


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
                        {{--<ul class="sub">--}}
                            {{--<li class="active"><a  href="javascript#">{{$data[0]->getCompanyname->c_companyname}}</a></li>--}}

                        {{--</ul>--}}
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
                        <a class="active" href="{{ url('/showStdevices') }}">
                            <i class="fa fa-th"></i>
                            <span>扫描笔设备表</span>
                        </a>
                    </li>

                </ul>
                <!-- sidebar menu end-->
            </div>
        </aside>
        <!--sidebar end-->

        <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <h3><i class="fa fa-angle-right"></i><a href="{{url('/tableshow')}}" style="color: #656565;"> 扫描笔设备表</a></h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-panel">
                            <h4><i class="fa fa-angle-right"></i> St01 &nbsp&nbsp&nbsp( 设备总数：{{$count}} ) </h4>
                            <hr>
                            <table class="table table-striped table-advance table-hover">

                                <thead>
                                <tr>
                                    <th><i class="fa fa-lightbulb-o"></i> ID</th>
                                    <th><i class="fa fa-desktop"></i> Mac</th>
                                    {{--<th class="hidden-phone"><i class="fa fa-question-circle"></i> Descrition</th>--}}
                                    <th><i class="fa fa-paper-plane"></i> Cpuid</th>
                                    {{--<th><i class="fa fa-calendar-check-o"></i> 天数</th>--}}
                                    {{--<th><i class="fa fa-chevron-left"></i> Starttime</th>--}}
                                    {{--<th>Endtime <i class="fa fa-chevron-right"></i></th>--}}
                                    {{--<th><i class="fa fa-university"></i> Company</th>--}}
                                    <th><i class="fa fa-terminal"></i> Version</th>
                                    {{--<th><i class="fa fa-user"></i> name</th>--}}
                                    {{--<th><i class="fa fa-phone-square"></i> Tel</th>--}}
                                    <th><i class="fa fa-bell "></i> 访问量</th>
                                    <th><i class="fa fa-bell "></i> 最近访问时间</th>
                                    <th><i class="fa fa-bell "></i> 数据收集</th>
                                    <th><i class="fa fa-edit"></i> Edit</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($data as $item)
                                    {{--@if($item['status'] == 3)--}}
                                        {{--@continue--}}
                                    {{--@endif--}}
                                    <tr>
                                        <td>{{$item['st_id']}}</td>
                                        <td>{{$item['st_mac']}}</td>
                                        <td>{{$item['st_chipid']}}</td>
                                        {{--<td>{{$item['t']}}</td>--}}
                                        {{--<td>{{$item['d_starttime']}}</td>--}}
                                        {{--<td>{{$item['d_endtime']}}</td>--}}
                                        {{--<td>{{$item->getCompanyname->c_companyname}}</td>--}}
                                        <td>{{$item['st_version']}}</td>
                                        {{--<td>{{$item['d_name']}}</td>--}}
                                        {{--<td>{{$item['d_tel']}}</td>--}}
                                        <td>{{$item['st_liveness']}} 次</td>
                                        <td>{{$item['updated_at']}}</td>
                                        @if($item['st_collectStatus'] == 1)
                                            <td><i class="fa fa-times "></i></td>
                                        @elseif($item['st_collectStatus'] == 2)
                                            <td><i class="fa fa-check "></i></td>
                                        @endif
                                        <td>
                                            {{--<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>--}}
                                            <a href="{{url('/showStdevices/editStdevice/'.$item['st_id'])}}"><button class="btn btn-primary btn-xs" ><i
                                                            class="fa fa-pencil"></i></button></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                            {{ $data->links() }}
                            {{--<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">--}}
                        </div><!-- /content-panel -->
                    </div><!-- /col-md-12 -->
                </div><!-- /row -->

            </section>
            <! --/wrapper -->
        </section><!-- /MAIN CONTENT -->

    </section>


@endsection
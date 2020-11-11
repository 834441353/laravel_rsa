@extends('home.common')
@section('content')
    <script language="javascript">
        var id = 0;

        function setdelid(delid) {
            id = delid;
            console.log(id)
        }

        function delbutton() {
            if (id == 0) {
                console.error("cuowu");
            }
            $.ajax({
                type: 'POST',
                url: '{{ url('tableshow/company/dela') }}',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    // _method: 'dela',
                    id: id,
                },
                success: function (data) {
                    if (data.status) {
                        console.log(data);
                        toastr.success('删除成功！');
                        // sleep(1000);
                        location.reload();
                    } else {
                        // location.reload();
                        console.log(data);
                        toastr.warning('删除失败！');
                    }
                },
                error: function (data) {
                    console.info(data);
                },
            });
        }
    </script>
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
                        <a class="active" href="{{ url('tableshow') }}">
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

        <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <h3><i class="fa fa-angle-right"></i><a href="{{url('/tableshow')}}" style="color: #656565;"> 公司列表</a></h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-panel">
                            <h4><i class="fa fa-angle-right"></i> {{$data[0]->getCompanyname->c_companyname}}</h4>
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
                                    <th><i class="fa fa-bookmark"></i> Productname</th>
                                    <th><i class="fa fa-terminal"></i> Version</th>
                                    {{--<th><i class="fa fa-user"></i> name</th>--}}
                                    {{--<th><i class="fa fa-phone-square"></i> Tel</th>--}}
                                    <th><i class="fa fa-bell "></i> 授权访问</th>
                                    <th><i class="fa fa-bell "></i> 最近授权时间</th>
                                    <th><i class="fa fa-bell "></i> 数据收集</th>
                                    <th><i class="fa fa-edit"></i> Status</th>
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
                                        <td>{{$item['d_did']}}</td>
                                        <td>{{$item['d_mac']}}</td>
                                        <td>{{$item['d_chipid']}}</td>
                                        {{--<td>{{$item['t']}}</td>--}}
                                        {{--<td>{{$item['d_starttime']}}</td>--}}
                                        {{--<td>{{$item['d_endtime']}}</td>--}}
                                        {{--<td>{{$item->getCompanyname->c_companyname}}</td>--}}
                                        <td>{{$item['d_productname']}}</td>
                                        <td>{{$item['d_version']}}</td>
                                        {{--<td>{{$item['d_name']}}</td>--}}
                                        {{--<td>{{$item['d_tel']}}</td>--}}
                                        <td>{{$item['d_liveness']}} 次</td>
                                        <td>{{$item['updated_at']}}</td>
                                        @if($item['d_collectStatus'] == 1)
                                            <td><i class="fa fa-times "></i></td>
                                        @elseif($item['d_collectStatus'] == 2)
                                            <td><i class="fa fa-check "></i></td>
                                        @endif
                                        @if($item['status'] == 1)
                                            <td><span class="label label-success label-mini">正常</span></td>
                                        @elseif($item['status'] == 2)
                                            <td><span class="label label-info label-mini">停用</span></td>
                                        @elseif($item['status'] == 3)
                                            <td><span class="label label-warning label-mini">删除</span></td>
                                        @elseif($item['status'] == 4)
                                            {{--<td><span class="label label-theme label-mini">过期</span></td>--}}
                                            <td><span class="label label-success label-mini">正常</span></td>
                                        @elseif($item['status'] == 5)
                                            <td><span class="label label-primary label-mini">未激活</span></td>
                                        @endif
                                        <td>
                                            {{--<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>--}}
                                            <a href="{{url('tableshow/company/editItem/'.$item['d_id'])}}"><button class="btn btn-primary btn-xs" ><i
                                                            class="fa fa-pencil"></i></button></a>
                                            <button class="btn btn-danger btn-xs" data-toggle="modal"
                                                    data-target="#myModal" onclick="setdelid({{$item['d_id']}})"><i
                                                        class="fa fa-trash-o "></i></button>

                                        </td>
                                        <!-- Modal -->
                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">&times;
                                                        </button>
                                                        <h4 class="modal-title" id="myModalLabel">提示</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        确定删除？
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">取消
                                                        </button>
                                                        <button type="button" class="btn btn-primary"
                                                                onclick="delbutton()">确定
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
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
                url: '{{ url('tableshow/dela') }}',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    // _method: 'dela',
                    id: id,
                },
                success: function (data) {
                    if (data.status) {
                        console.log(data);
                        location.reload();
                    } else {
                        // location.reload();
                        console.log(data);
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
                <h3><i class="fa fa-angle-right"></i> 公司列表</h3>
                <div class="row">

                    <div class="col-md-12">
                        <div class="content-panel">
                            <h4><i class="fa fa-angle-right"></i> 公司列表</h4>
                            <hr>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>公司名</th>
                                    <th>设备总数</th>
                                    <th>注册时间</th>
                                    <th>描述</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                <tr>
                                    <td>{{$item["c_id"]}}</td>
                                    <td><a href="{{url("tableshow/company/".$item["c_id"])}}">{{$item["c_companyname"]}}</a></td>
                                    <th>{{$item->getDevices->count()}} 台</th>
                                    <td>{{$item["created_at"]}}</td>
                                    <td>{{$item["comment"]}}</td>
                                </tr>
                                @endforeach

                                </tbody>

                            </table>
                            {{ $data->links() }}
                            {{--<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">--}}
                        </div><! --/content-panel -->
                    </div><!-- /col-md-12 -->
                </div><!-- row -->
            </section>
            <! --/wrapper -->
        </section><!-- /MAIN CONTENT -->

    </section>


@endsection
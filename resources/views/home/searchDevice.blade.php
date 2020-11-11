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

        $(function () {
            console.info('111');
            $('form').submit(function () {
                return false;
            });
            // 用button点击发送ajax请求来完成数据交互 => 局部刷新
            $('button').click(function () {
                console.info('ajax..');
                var searchType = $('#searchType').val();
                var searchValue = $('#searchValue').val();
                // console.info(d_chipid);

                $.ajax({
                    type: 'POST',
                    url: '{{url('/tableshow/searchDevice')}}',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        searchType: searchType,
                        searchValue: searchValue,
                    },
                    success: function (data) {
                        console.log(data.message);
                        if (data.status) {
                            // console.log(data.message);
                            document.getElementById('mydiv').style.display= 'block';
                            document.getElementById('mydiv').innerHTML = data.message;
                        } else {
                            // location.reload();
                            // console.log(data);
                            document.getElementById('mydiv').style.display= 'none';
                            console.log(data.message.toString());
                            toastr.warning(data.message.toString());
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        // console.info(data.message.toString());
                        // toastr.warning(data.message.toString());
                    },
                });

            });

        })

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

                    <p class="centered"><a href="{{ url('tableshow') }}"><img
                                    src="{{ asset('index') }}/assets/img/ui-sam.jpg"
                                    class="img-circle" width="60"></a></p>
                    <h5 class="centered">YLSJ_AuthorizationSystems</h5>

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
                            <li><a href="{{url('/tableshow/addCompany')}}">添加公司</a></li>
                            <li><a href="{{url('/tableshow/addDevice')}}">添加设备</a></li>
                            <li><a href="{{url('/tableshow/addDid')}}">添加ID</a></li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-th"></i>
                            <span>导入</span>
                        </a>
                        <ul class="sub">
                            <li><a href="morris.html">导入设备列表</a></li>
                            {{--<li><a  href="chartjs.html">导入公司列表</a></li>--}}
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a class="active" href="{{url('/tableshow/searchDevice')}}">
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
                <h3><i class="fa fa-angle-right"></i> 设备查询</h3>

                <!-- INLINE FORM ELELEMNTS -->
                <div class="row mt">
                    <div class="col-lg-12">
                        <div class="form-panel" >
                            <h4 class="mb"><i class="fa fa-angle-right"></i> 设备信息</h4>
                            <form class="form-inline" role="form">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <select class="combobox form-control" id="searchType" name="searchType">
                                        <option value="d_did"> 授权码ID</option>
                                        <option value="d_chipid"> CHIPID</option>
                                        <option value="d_mac"> MAC地址</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="searchValue" name="searchValue">
                                </div>
                                <button type="submit" class="btn btn-theme">查询</button>
                            </form>
                            <div class="content-panel" id="mydiv" style="display: none;"></div>
                        </div><!-- /form-panel -->
                    </div><!-- /col-lg-12 -->
                </div><!-- /row -->
            </section>
            <! --/wrapper -->
        </section><!-- /MAIN CONTENT -->

    </section>


@endsection
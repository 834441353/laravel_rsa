@extends('home.common')
@section('content')

    <script language="javascript">
        // toastr.options.positionClass = 'toast-bottom-right';
        // toastr.options.positionClass = 'toast-bottom-center';
        $(function () {
            console.info('111');
            $('form').submit(function () {
                return false;
            });
            // 用button点击发送ajax请求来完成数据交互 => 局部刷新
            $('button').click(function () {
                console.info('ajax..');
                var st_id = $('#st_id').val();
                var st_mac = $('#st_mac').val();
                var st_chipid = $('#st_chipid').val();
                // var d_version = $('#d_version').val();
                var st_collectStatus = $('#st_collectStatus').val();

                $.ajax({
                    type: 'POST',
                    url: '{{url('/showStdevices/editStdevice')}}',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        st_id: st_id,
                        st_mac: st_mac,
                        st_chipid:st_chipid,
                        // d_version: d_version,
                        st_collectStatus:st_collectStatus,

                    },
                    success: function (data) {
                        if (data.status) {
                            console.log(data);
                            toastr.success('修改成功！');

                            window.location.href = "{{url('/showStdevices')}}";

                        } else {
                            // location.reload();
                            console.log(data.message.toString());
                            toastr.warning(data.message.toString());
                        }
                    },
                    error: function (data) {
                        console.info(data);
                        // console.info(data.message.toString());
                        toastr.warning(data.message.toString());
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
                            <li><a href="{{url('/tableshow/addCompany')}}">添加公司</a></li>
                            <li><a href="{{url('/tableshow/addDevice')}}">添加设备</a></li>
                            <li><a  href="{{url('/tableshow/addDid')}}">添加ID</a></li>
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

                <h3><i class="fa fa-angle-right"></i><a href="{{url('/tableshow')}}" style="color: #656565;"> 扫描笔设备修改</a></h3>

                <!-- BASIC FORM ELELEMNTS -->

                <div class="row mt">
                    <div class="col-lg-12">
                        <div class="form-panel">
                            <h4 class="mb"><i class="fa fa-angle-right"></i> 设备信息</h4>
                            <form class="form-horizontal style-form" method="post"
                                  action="{{url('/showStdevices/editStdevice')}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">Mac地址</label>
                                    <div class="col-sm-10">
                                        <input type="test" style="display:none" id="st_id" name="st_id"
                                               value="{{$data["st_id"]}}">
                                        <input type="text" class="form-control" id="st_mac" name="st_mac" readonly
                                               value="{{$data["st_mac"]}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">CPU序列号</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="st_chipid" name="st_chipid" readonly
                                               value="{{$data["st_chipid"]}}">
                                        {{--<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>--}}
                                    </div>
                                </div>



                                {{--<div class="form-group">--}}
                                    {{--<label class="col-sm-2 col-sm-2 control-label">算法版本</label>--}}
                                    {{--<div class="col-sm-10">--}}
                                        {{--<input type="text" class="form-control" placeholder="placeholder">--}}
                                        {{--<input type="text" class="form-control" id="d_version" name="d_version"--}}
                                               {{--value="{{$data["d_version"]}}">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">数据收集</label>
                                    <div class="col-sm-10">
                                        {{--<input type="password" class="form-control" placeholder="">--}}
                                        {{--<input type="tel" class="form-control" id="d_tel" name="d_tel" value="{{$data["d_tel"]}}">--}}
                                        {{--<button type="button" class></button>--}}
                                        <select class="combobox form-control" id="st_collectStatus" name="st_collectStatus">

                                            <option value="1" {{$data["st_collectStatus"]==1?"selected":""}}>关闭</option>
                                            <option value="2" {{$data["st_collectStatus"]==2?"selected":""}}>打开</option>

                                        </select>
                                    </div>
                                </div>



                                <div class="form-group">
                                    {{--<label class="col-lg-2 col-sm-2 control-label">Static control</label>--}}
                                    <div class="col-lg-12">
                                        {{--<p class="form-control-static">email@example.com</p>--}}
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">提交</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div><!-- col-lg-12-->
                </div><!-- /row -->


            </section>
        </section>
    {{--<!--script for this page-->--}}
    {{--<script src="{{ asset('index') }}/assets/js/jquery-ui-1.9.2.custom.min.js"></script>--}}

    {{--<!--custom switch-->--}}
    {{--<script src="{{ asset('index') }}/assets/js/bootstrap-switch.js"></script>--}}

    {{--<!--custom tagsinput-->--}}
    {{--<script src="{{ asset('index') }}/assets/js/jquery.tagsinput.js"></script>--}}

    {{--<!--custom checkbox & radio-->--}}

    {{--<script type="text/javascript"--}}
    {{--src="{{ asset('index') }}/assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>--}}
    {{--<script type="text/javascript"--}}
    {{--src="{{ asset('index') }}/assets/js/bootstrap-daterangepicker/date.js"></script>--}}
    {{--<script type="text/javascript"--}}
    {{--src="{{ asset('index') }}/assets/js/bootstrap-daterangepicker/daterangepicker.js"></script>--}}

    {{--<script type="text/javascript"--}}
    {{--src="{{ asset('index') }}/assets/js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>--}}


    {{--<script src="{{ asset('index') }}/assets/js/form-component.js"></script>--}}




@endsection
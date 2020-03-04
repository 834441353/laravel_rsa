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
                var d_id = $('#d_id').val();
                var d_mac = $('#d_mac').val();
                var d_chipid = $('#d_chipid').val();
                var d_starttime = $('#d_starttime').val();
                var d_endtime = $('#d_endtime').val();
                var d_companyid = $('#d_companyid').val();
                var d_productname = $('#d_productname').val();
                var d_version = $('#d_version').val();
                var d_name = $('#d_name').val();
                var d_tel = $('#d_tel').val();
                var status = $('#status').val();

                $.ajax({
                    type: 'POST',
                    url: '{{url('tableshow/company/editItem')}}',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        d_id: d_id,
                        d_mac: d_mac,
                        d_chipid: d_chipid,
                        d_starttime: d_starttime,
                        d_endtime: d_endtime,
                        d_companyid: d_companyid,
                        d_productname: d_productname,
                        d_version: d_version,
                        d_name: d_name,
                        d_tel: d_tel,
                        status: status,
                    },
                    success: function (data) {
                        if (data.status) {
                            console.log(data);
                            toastr.success('修改成功！');

                            window.location.href = "{{url('/tableshow/company/'.$data["d_companyid"])}}";

                        } else {
                            // location.reload();
                            console.log(data.message.toString());
                            toastr.warning(data.message.toString());
                        }
                    },
                    error: function (data) {
                        console.info(data.message.toString());
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

                    <p class="centered"><a href="profile.html"><img src="{{ asset('index') }}/assets/img/ui-sam.jpg"
                                                                    class="img-circle" width="60"></a></p>
                    <h5 class="centered">Marcel Newman</h5>

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
                            <li><a href="{{url('/tableshow/addCompany')}}">添加公司</a></li>
                            <li><a href="{{url('/tableshow/addDevice')}}">添加设备</a></li>
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

                <h3><i class="fa fa-angle-right"></i><a href="{{url('/tableshow')}}" style="color: #656565;">
                        >公司列表</a><a href="{{url('/tableshow/company/'.$data['d_companyid'])}}" style="color: #656565;">
                        >设备列表</a></h3>

                <!-- BASIC FORM ELELEMNTS -->

                <div class="row mt">
                    <div class="col-lg-12">
                        <div class="form-panel">
                            <h4 class="mb"><i class="fa fa-angle-right"></i> 设备信息</h4>
                            <form class="form-horizontal style-form" method="post"
                                  action="{{url('tableshow/company/editItem')}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">Mac地址</label>
                                    <div class="col-sm-10">
                                        <input type="test" style="display:none" id="d_id" name="d_id"
                                               value="{{$data["d_id"]}}">
                                        <input type="text" class="form-control" id="d_mac" name="d_mac" readonly
                                               value="{{$data["d_mac"]}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">CPU序列号</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="d_chipid" name="d_chipid"
                                               value="{{$data["d_chipid"]}}">
                                        {{--<span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>--}}
                                    </div>
                                </div>
                                <div class="form-group form-inline">
                                    <label class="col-sm-2 col-sm-2 control-label">授权时间段</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" class="form-control round-form" id="d_starttime" name="d_starttime" value="{{date('Y-m-d\TH:m:s',strtotime($data["d_starttime"]))}}">
                                        <span>  -  </span>
                                        <input type="datetime-local" class="form-control round-form" id="d_endtime" name="d_endtime" value="{{date('Y-m-d\TH:m:s',strtotime($data["d_endtime"]))}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">公司名</label>
                                    <div class="col-sm-10">
                                        {{--<input class="form-control" id="focusedInput" type="text" value="This is focused...">--}}
                                        <select class="combobox form-control" id="d_companyid" name="d_companyid">
                                            @foreach( $data_company as $item)
                                                <option value="{{$item["c_id"]}}" {{$data["d_companyid"]==$item["c_id"]?"selected":""}}>{{$item["c_companyname"]}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">产品名</label>
                                    <div class="col-sm-10">
                                        {{--<input class="form-control" id="disabledInput" type="text" placeholder="Disabled input here..." disabled>--}}
                                        <input type="text" class="form-control" id="d_productname" name="d_productname"
                                               value="{{$data["d_productname"]}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">算法版本</label>
                                    <div class="col-sm-10">
                                        {{--<input type="text" class="form-control" placeholder="placeholder">--}}
                                        <input type="text" class="form-control" id="d_version" name="d_version"
                                               value="{{$data["d_version"]}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">紧急联系人</label>
                                    <div class="col-sm-10">
                                        {{--<input type="password" class="form-control" placeholder="">--}}
                                        <input type="text" class="form-control" id="d_name" name="d_name"
                                               value="{{$data["d_name"]}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">紧急联系人电话</label>
                                    <div class="col-sm-10">
                                        {{--<input type="password" class="form-control" placeholder="">--}}
                                        <input type="tel" class="form-control" id="d_tel" name="d_tel"
                                               value="{{$data["d_tel"]}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">状态</label>
                                    <div class="col-sm-10">
                                        {{--<input type="password" class="form-control" placeholder="">--}}
                                        {{--<input type="tel" class="form-control" id="d_tel" name="d_tel" value="{{$data["d_tel"]}}">--}}
                                        {{--<button type="button" class></button>--}}
                                        <select class="combobox form-control" id="status" name="status">

                                            <option value="1" {{$data["status"]==1?"selected":""}}>正常</option>
                                            <option value="2" {{$data["status"]==2?"selected":""}}>停用</option>
                                            <option value="3" {{$data["status"]==3?"selected":""}}>删除</option>
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
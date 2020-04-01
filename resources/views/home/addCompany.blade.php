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
                // var d_id = $('#d_id').val();
                var c_companyname = $('#c_companyname').val();
                var comment = $('#comment').val();


                $.ajax({
                    type: 'POST',
                    url: '{{url('/tableshow/addCompany')}}',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        // d_id: d_id,
                        c_companyname: c_companyname,
                        comment: comment,

                    },
                    success: function (data) {
                        if (data.status) {
                            console.log(data);
                            toastr.success('添加成功！');

                            window.location.href = "{{url('/tableshow/')}}";

                        } else {
                            // location.reload();
                            // console.log(data);
                            console.log(data.message.toString());
                            toastr.warning(data.message.toString());
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        console.info(data.message.toString());
                        toastr.warning(data.message.toString());
                    },
                });

            });

        })

    </script>
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
                        <li class="active"><a  href="{{url('/tableshow/addCompany')}}">添加公司</a></li>
                        <li><a  href="{{url('/tableshow/addDevice')}}">添加设备</a></li>
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
            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->
    <section id="main-content">
        <section class="wrapper">

            <h3><i class="fa fa-angle-right"></i> 添加公司</h3>

            <!-- BASIC FORM ELELEMNTS -->

            <div class="row mt">
                <div class="col-lg-12">
                    <div class="form-panel">
                        <h4 class="mb"><i class="fa fa-angle-right"></i> 公司信息</h4>
                        <form class="form-horizontal style-form" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">公司名</label>
                                <div class="col-sm-10">
                                    {{--<input type="test" style="display:none" id="d_id" name="d_id">--}}
                                    <input type="text" class="form-control" id="c_companyname" name="c_companyname">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">备注</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="comment" name="comment">

                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-lg-12">

                                    <button type="submit" class="btn btn-primary btn-lg btn-block">提交</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div><!-- col-lg-12-->
            </div><!-- /row -->


        </section>
    </section>


@endsection
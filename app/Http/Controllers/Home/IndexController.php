<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Model\PlatformModel;
use Illuminate\Http\Request;
use App\Model\DeviceModel;
use App\Model\CompanyModel;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Validator;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        return view('home.index');
    }

    //展示公司列表
    public function tableshow(DeviceModel $deviceModel, CompanyModel $companyModel)
    {
        $data = $companyModel->paginate(13);
        return view('home.tableshow', compact('data'));
    }


    //展示所属公司的设备列表
    public function showCompany(Request $request, CompanyModel $companyModel, DeviceModel $deviceModel)
    {
        $id = $request->id;
        $data = $deviceModel->where("d_companyid", $id)->paginate(13);
//        $data['companyname'] = $companyModel->where("c_id",$id)->first()->c_companyname;
//        dd($data[0]);
        if ($data->count() == 0) {
            $data = $companyModel->paginate(13);
            return view('home.tableshow', compact('data'));
        }
        for ($item = 0; $item < $data->count(); $item++) {
//            var_dump($data[$item]['d_endtime']);
            if (strtotime($data[$item]['d_starttime']) > strtotime(date('Y-m-d H:i:s'))) {
//                $data[$item]['t'] = $data[$item]['d_starttime'];
                $data[$item]['t'] = '未开始';
            } else {
                $t = round((strtotime($data[$item]['d_endtime']) - strtotime(date('Y-m-d H:i:s'))) / 86400, 2);
                if ($data[$item]['status'] == 1) {
                    if ($t < 0) {
                        $data[$item]['status'] = 4;
                    }
                }
                $data[$item]['t'] = $t;
            }
            if ($data[$item]['d_mac'] == null or $data[$item]['d_chipid'] == null) {
                $data[$item]['status'] = 5;
            }
        }
        return view('home.companyDTshow', compact('data'));
    }


    //修改设备信息
    public function edita(Request $request, DeviceModel $deviceModel, CompanyModel $companyModel)
    {

        if ($request->isMethod("get")) {
            $id = $request->id;
            $data = $deviceModel->find($id);
            $data_company = $companyModel->get();
//            dd($data);
            return view('home.editItem', ["data" => $data, "data_company" => $data_company]);
        } elseif ($request->isMethod("post")) {
            $data = $request->request->all();
//            dd($data);
            $check = [
//                'd_chipid'=>'required',
                'd_version' => 'required',
                'd_productname' => 'required',
                'd_name' => 'required',
                'd_tel' => 'required',
            ];
            $messages = [
//                'd_chipid.required' => 'chipid不能为空！',
                'd_version.required' => '算法版本不能为空！',
                'd_productname.required' => '产品名不能为空！',
                'd_name.required' => '紧急联系人不能为空！',
                'd_tel.required' => '紧急联系人电话不能为空！',
            ];
            $status = Validator::make($data, $check, $messages);
            if ($status->fails()) {
                return ['status' => false, 'message' => $status->messages()->all()];
            }
            $status = $deviceModel->where("d_id", $data['d_id'])->update(['d_chipid' => $data['d_chipid'], 'd_starttime' => $data['d_starttime'], 'd_endtime' => $data['d_endtime'], 'd_companyid' => $data['d_companyid'], 'd_productname' => $data['d_productname'], 'd_version' => $data['d_version'], 'd_name' => $data['d_name'], 'd_tel' => $data['d_tel'], 'd_collectStatus' => $data['d_collectStatus'], 'status' => $data['status']]);
            if ($status) {
                return ['status' => true, 'message' => ['添加成功！']];
            } else {
                return ['status' => false, 'message' => ['添加失败！']];
            }
        }
    }

    //删除设备
    public function dela(Request $request, DeviceModel $deviceModel)
    {
        if ($request->isMethod('post')) {
            if ($request->request->has('id')) {
                $val = $request->request->get('id');
                $status = $deviceModel->where("d_id", $val)->update(["status" => 3]);
                if ($status) {
                    return ['status' => true, 'message' => $status];
                } else {
                    return ['status' => false, 'message' => 0];
                }

            }
            return ['status' => false, 'message' => 0];
        }
        return 22;
    }


    //添加设备
    public function addDevice(Request $request, DeviceModel $deviceModel, CompanyModel $companyModel, PlatformModel $platformModel)
    {
        if ($request->isMethod('get')) {
            $data_company = $companyModel->get();
            $data_platform = $platformModel->get();
            return view('home.addDevice', ["data_company" => $data_company, "data_platform" => $data_platform]);
        } elseif ($request->isMethod("post")) {
            $data = $request->request->all();
//            dd($data);
            $check = [
                'd_mac' => 'required',
                'd_chipid' => 'required',
                'd_version' => 'required',
                'd_productname' => 'required',
                'd_name' => 'required',
                'd_tel' => 'required',
            ];
            $messages = [
                'd_mac.required' => 'Mac地址不能为空',
                'd_chipid.required' => 'chipid不能为空！',
                'd_version.required' => '算法版本不能为空！',
                'd_productname.required' => '产品名不能为空！',
                'd_name.required' => '紧急联系人不能为空！',
                'd_tel.required' => '紧急联系人电话不能为空！',
            ];
            $status = Validator::make($data, $check, $messages);
            if ($status->fails()) {
                return ['status' => false, 'message' => $status->messages()->all()];
            }
//            $status = $deviceModel->where('d_mac',$data['d_mac'])->first();
//            if($status != null){
//                return ['status'=>false,'message'=>['该设备已存在！']];
//            }
//            $status = $deviceModel->where('d_chipid',$data['d_chipid'])->first();
//            if($status != null){
//                return ['status'=>false,'message'=>['该设备已存在！']];
//            }
//            var_dump($data['d_chipid']);

            $dCount = $deviceModel->count();
            if (!$dCount) {
                return ['status' => false, 'message' => ['添加失败！']];
            }
            $num_str = (string)($dCount + 1);
            $num_strlength = strlen($num_str);
            $length = 7;
            if ($length-- > $num_strlength) {
                $num_str = str_pad($num_str, $length, "0", STR_PAD_LEFT);
            }
            $pid_num_str = (string)($data['p_id']);
            $pid_num_strlength = strlen($pid_num_str);
            $length = 3;
            if ($length-- > $pid_num_strlength) {
                $pid_num_str = str_pad($pid_num_str, $length, "0", STR_PAD_LEFT);
            }

            $id_tmp = time() . $pid_num_str . rand(1000, 9999) . $num_str;


            $status = $deviceModel->create(['d_did' => $id_tmp, 'd_mac' => $data['d_mac'], 'd_starttime' => $data['d_starttime'], 'd_chipid' => $data['d_chipid'], 'd_endtime' => $data['d_endtime'], 'd_companyid' => $data['d_companyid'], 'd_productname' => $data['d_productname'], 'd_version' => $data['d_version'], 'd_name' => $data['d_name'], 'd_tel' => $data['d_tel'], 'd_collectStatus' => $data['d_collectStatus'], 'status' => $data['status']]);
            if ($status) {
//                return ['status'=>true,'message'=>['添加成功！']];
                return ['status' => true, 'message' => $id_tmp];
            } else {
                return ['status' => false, 'message' => ['添加失败！']];
            }
        }
        return view('home.additem');
    }

    //添加公司
    public function addCompany(Request $request, CompanyModel $companyModel)
    {
        if ($request->isMethod('get')) {
            return view('home.addCompany');
        } elseif ($request->isMethod('post')) {
            $data = $request->request->all();
            $status = $companyModel->where('c_companyname', $data['c_companyname'])->first();
            if ($status != null) {
                return ['status' => false, 'message' => ['该公司已存在！']];
            }
            $status = $companyModel->create(['c_companyname' => $data['c_companyname'], 'comment' => $data['comment']]);
            if ($status) {
                return ['status' => true, 'message' => ['添加成功！']];
            } else {
                return ['status' => false, 'message' => ['添加失败！']];
            }
        }
    }

    //图片回传
    public function imgRecevice(Request $request, DeviceModel $deviceModel)
    {
        if ($request->isMethod('post')) {
//            return $request->request->all();
            if (!$request->request->has('mac')) {
                return -2;//非法请求，无关键字为info的数据内容
            }
            //$deviceid = $request->request->get('deviceid');
            $mac = $request->request->get('mac');
            if($mac == null){
                return -2;
            }
//            $fileinfo = $request->request->get('fileinfo');
            //dd($request->request);
            if ($request->hasFile('fileinfo')) {
                $file = $request->file('fileinfo');//获取文件
                if (!$file) {
                    return -2;//非法请求，无关键词为fileinfo的文件内容
                }
                $fileName = $file->getClientOriginalName();
//            $savePath = $id.'/'.strtotime(date("Y-m-d H:i:s")).'_'.$fileName;
                $savePath = 'face/'.$mac . '/' . md5(time() . rand(1000, 9999)) . '_' . $fileName;
                $status = Storage::put($savePath, File::get($file));//通过Storage put方法存储   File::get获取到的是文件内容
//            $status = Storage::put($savePath, $fileinfo);//通过Storage put方法存储   File::get获取到的是文件内容
                if (!$status) {
                    return -2;//服务器错误
                }
                return 1;
            } else {
                //$now_time = strtotime(date("Y-m-d H:i:s"));
                $data = $deviceModel->where('d_mac', $mac)->orderBy('updated_at', 'desc')->first();
//                dd($data);

                if (!$data) {
                    return -2;
                }
                $collectStatus = $data->d_collectStatus;
                return $collectStatus;
            }

        } else {
            return -2;//非法请求,请求方式不为post
        }

    }

    //生成并添加设备ID
    public function createDid(Request $request, CompanyModel $companyModel, DeviceModel $deviceModel, PlatformModel $platformModel)
    {
        if ($request->isMethod('get')) {
            $data_company = $companyModel->get();
            $data_platform = $platformModel->get();
            return view('home.addDid', ["data_company" => $data_company, "data_platform" => $data_platform]);
        } elseif ($request->isMethod("post")) {
            $data = $request->request->all();
//            dd($data);
            $check = [
                'd_didnum' => 'required',
                'd_version' => 'required',
                'd_productname' => 'required',
                'd_name' => 'required',
                'd_tel' => 'required',
            ];
            $messages = [
                'd_didnum.required' => '设备数不能为空！',
                'd_version.required' => '算法版本不能为空！',
                'd_productname.required' => '产品名不能为空！',
                'd_name.required' => '紧急联系人不能为空！',
                'd_tel.required' => '紧急联系人电话不能为空！',
            ];
            $status = Validator::make($data, $check, $messages);
            if ($status->fails()) {
                return ['status' => false, 'message' => $status->messages()->all()];
            }
            $dCount = $deviceModel->count();

            if (!$dCount) {
                return ['status' => false, 'message' => ['添加失败！']];
            }
            $did_array = array();
            for ($i = 1; $i <= $data['d_didnum']; $i++) {
                $num_str = (string)($dCount + $i);
                $num_strlength = strlen($num_str);
                $length = 7;
                if ($length-- > $num_strlength) {
                    $num_str = str_pad($num_str, $length, "0", STR_PAD_LEFT);
                }
                $pid_num_str = (string)($data['p_id']);
                $pid_num_strlength = strlen($pid_num_str);
                $length = 3;
                if ($length-- > $pid_num_strlength) {
                    $pid_num_str = str_pad($pid_num_str, $length, "0", STR_PAD_LEFT);
                }

                $id_tmp = time() . $pid_num_str . rand(1000, 9999) . $num_str;
//                dd($id_tmp,$pid_num_str);
                $status = $deviceModel->create(['d_did' => $id_tmp, 'd_mac' => null, 'd_starttime' => $data['d_starttime'], 'd_chipid' => null, 'd_endtime' => $data['d_endtime'], 'd_companyid' => $data['d_companyid'], 'd_productname' => $data['d_productname'], 'd_version' => $data['d_version'], 'd_name' => $data['d_name'], 'd_tel' => $data['d_tel'], 'd_collectStatus' => $data['d_collectStatus'], 'status' => $data['status']]);
                if (!$status) {
                    return ['status' => false, 'message' => ['添加失败！']];
                }
                array_push($did_array, $id_tmp);
            }

            if ($status) {
//                return ['status'=>true,'message'=>['添加成功！']];
                return ['status' => true, 'message' => $did_array];
            } else {
                return ['status' => false, 'message' => ['添加失败！']];
            }
        }
        return view('home.addDid');
    }

    public function showDid(Request $request)
    {

        $ids = $request->id;
        return view('home.addDidShow', ["idinfo" => $ids]);
    }

    public function importexcel(DeviceModel $deviceModel)
    {
        return view('home.importexcel');
    }

    public function searchDevice(Request $request, CompanyModel $companyModel, DeviceModel $deviceModel)
    {
        if ($request->isMethod('get')) {
            return view('home.searchDevice');
        } elseif ($request->isMethod("post")) {
            $data = $request->request->all();
            $check = [
                'searchType' => 'required',
                'searchValue' => 'required',
            ];
            $messages = [
                'searchType.required' => '查询种类不能为空！',
                'searchValue.required' => '内容不能为空！',
            ];
            $status = Validator::make($data, $check, $messages);
            if ($status->fails()) {
                return ['status' => false, 'message' => $status->messages()->all()];
            }
            if($data['searchType'] == "d_did"){
                $d_data = $deviceModel -> where("d_did",$data['searchValue'])->paginate(13);
            }elseif($data['searchType'] == "d_chipid"){
                $d_data = $deviceModel -> where("d_chipid",$data['searchValue'])->paginate(13);
            }elseif($data['searchType'] == "d_mac"){
                $d_data = $deviceModel -> where("d_mac",$data['searchValue'])->paginate(13);
            }else{
                return ['status' => false, 'message' => "查询失败"];
            }
//            dd($d_data->toArray()['data']);
            if($d_data==null){
                return ['status' => false, 'message' => "无该设备信息"];
            }else{
                if($d_data->toArray()['data'] == []){
                    return ['status' => false, 'message' => "无该设备信息"];
                }
                return ['status' => true, 'message' =>view('home.searchDeviceshow', compact('d_data'))->render() ];
            }
        }else{
            return ['status' => false, 'message' => "查询失败"];
        }

    }
}

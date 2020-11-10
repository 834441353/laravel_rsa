<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Model\StdeviceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class StController extends Controller
{
    //
    public function addStdevice(Request $request, StdeviceModel $stdeviceModel)
    {
        if ($request->isMethod('post')) {
//            return $request->request->all();
            if (!$request->request->has('mac') or !$request->request->has('chipid')) {
                return -2;//非法请求，无关键字为info的数据内容
            }
            $mac = $request->request->get('mac');
            $chipid = $request->request->get('chipid');
            if($mac ==null or $chipid == null){
                return -2;
            }
            $data = $stdeviceModel->where('st_mac', $mac)->first();
            if ($data == null) {
                $status = $stdeviceModel->create(['st_mac' => $mac, 'st_chipid' => $chipid,'st_liveness'=>1]);
                if ($status) {
                    return 1;
                } else {
                    return -2;
                }
            }
            if ($data->st_chipid != $chipid) {
                return -2;
            }
            $livenessNum = $data->st_liveness;
            $livenessNum = (int)$livenessNum + 1;
            $status = $stdeviceModel->where('st_mac', $mac)->update(["st_liveness" => $livenessNum]);
            return 1;
        }
    }

    //图片回传
    public function imgRecevice(Request $request, StdeviceModel $stdeviceModel)
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
                $savePath ='st01/'. $mac . '/' . md5(time() . rand(1000, 9999)) . '_' . $fileName;
                $status = Storage::put($savePath, File::get($file));//通过Storage put方法存储   File::get获取到的是文件内容
//            $status = Storage::put($savePath, $fileinfo);//通过Storage put方法存储   File::get获取到的是文件内容
                if (!$status) {
                    return -2;//服务器错误
                }
                return 1;
            } else {
                //$now_time = strtotime(date("Y-m-d H:i:s"));
                $data = $stdeviceModel->where('st_mac', $mac)->orderBy('updated_at', 'desc')->first();
//                dd($data);

                if (!$data) {
                    return -2;
                }
                $collectStatus = $data->st_collectStatus;
                return $collectStatus;
            }

        } else {
            return -2;//非法请求,请求方式不为post
        }

    }
}

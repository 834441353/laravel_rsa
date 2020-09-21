<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\DeviceModel;
use App\Model\UpdaterecordModel;
use App\Model\DeviceicModel;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Input;

class DeviceauthorizeController extends Controller
{
    /**
     * 设备加密授权接口 1、用于软加密设备授权和下发证书  2、用于控制加密设备图像回传开关  3、用于授权移动端设备算法库更新  4、用于软加密设备活跃计数
     * @param Request $request 请求
     * @param DeviceModel $deviceModel  设备库
     * @param UpdaterecordModel $updaterecord  更新库
     * @return string -2:非法请求
     *                -1:无设备数据
     *                 0:算法版本错误，并拒绝更新
     *                 1:不在有效时间段内
     *                 2:设备停用
     *                 3:设备删除状态
     *                 4:chipid/Mac地址不正确
     *        rsa加密内容:授权成功，加密内容为证书
     *        MD5加密内容:算法版本错误，发送更新token
     *
     */
    public function index(Request $request, DeviceModel $deviceModel, UpdaterecordModel $updaterecord)
    {
        $val = '';
        $vals = '';
        if ($request->isMethod('post')) {
            if (!$request->request->has('info')) {
                return -2;
            }

//            $request->validate([
//                'info' => 'bail|required|min:173',
//            ]);
            $va = $request->request->get('info');
//            return $va;
            if ($va == null and strlen($va) < 173) {
                return -2;//非法请求
            }
            $miwen = substr($va, -172, 172);
            $newmiwen = str_ireplace(" ", "+", $miwen);
            $val = self::rsa_decode($newmiwen);
            $vals = substr($va, 0, strlen($va) - 172);

//            $myfile = fopen("testfile.txt", "w");
//            fwrite($myfile, $va);
//            fclose($myfile);
//            return $va;
            if (md5($vals) != $val) {
                return -2;//非法请求
            }
            $vals = json_decode($vals);
            if (!array_key_exists('mac', (array)$vals) or !array_key_exists('chipid', (array)$vals) or !array_key_exists('company', (array)$vals) or !array_key_exists('productname', (array)$vals) or !array_key_exists('version', (array)$vals) or !array_key_exists('name', (array)$vals) or !array_key_exists('tel', (array)$vals)) {
                return -2;
            }
            $data = $deviceModel->where('d_mac', $vals->mac)->first();
            if ($data != null) {
                if ($data->status == 1) {
                    if (strtotime($data->d_starttime) < strtotime(date("Y-m-d H:i:s")) and strtotime(date("Y-m-d H:i:s")) < strtotime($data->d_endtime)) {
                        if ($data->d_version == $vals->version) {
                            if ($data->d_chipid != $vals->chipid) {
                                return 4;//chipid 不正确
                            }
                            $data = array('mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => strtotime($data->d_starttime), 'endtime' => strtotime($data->d_endtime), 'company' => $vals->company, 'productname' => $vals->productname, 'version' => $vals->version, 'name' => $vals->name, 'tel' => $vals->tel);
                            $data = json_encode($data);
                            return self::authorization($data);
                        } else {
                            $datau = $updaterecord->where('u_mac', $vals->mac)->where('u_chipid', $vals->chipid)->where('u_oldversion', $vals->version)->first();
                            if ($datau != null) {
                                if ($datau->status == 0) {
                                    $token = md5($datau->u_id . $datau->u_mac . $datau->u_chipid . $datau->u_oldversion . $datau->u_newversion);
                                    return $token;
                                } else {
                                    return 0;
                                }
                            }
                            $uid = strtotime(date("Y-m-d H:i:s")) . rand(10000, 99999);
                            $status = $updaterecord->create([
                                'u_id' => $uid,
                                'u_mac' => $vals->mac,
                                'u_chipid' => $vals->chipid,
                                'u_oldversion' => $vals->version,
                                'u_newversion' => $data->d_version,
                                'status' => 0,
                            ]);
                            if (!$status) {
                                return 0;
                            }
                            $token = md5($uid . $vals->mac . $vals->chipid . $vals->version . $data->d_version);
                            return $token;//算法版本不正确
//                            return 0;//算法版本不正确
                        }
                    } else {
                        return 1;//不在有效时间段内
                    }
                } elseif ($data->status == 2) {
                    return 2;//该设备停用状态
                } elseif ($data->status == 3) {
                    return 3;//该设备删除状态
                }
            } else {
                return -1;//无该设备数据
            }
        } else {
            return -2;//非法请求
        }
    }

    /**
     * 下载适用算法库接口
     * @param Request $request ：请求
     * @param UpdaterecordModel $updaterecord 更新记录
     * @param DeviceModel $deviceModel 设备库
     * @return int|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request, UpdaterecordModel $updaterecord, DeviceModel $deviceModel)
    {
//        $files = './public/images/test.zip';
//        $name = basename($files);       // basename() 函数返回路径中的文件名部分。
//        return response()->download($files, $name, $headers = ['Content-Type' => 'application/zip;charset=utf-8']);

        if ($request->isMethod('post')) {

            if ($request->request->has('macid') and $request->request->has('chipid') and $request->request->has('version') and $request->request->has('token')) {

                $macid = $request->request->get('macid');
                $chipid = $request->request->get('chipid');
                $oldversion = $request->request->get('version');
                $token = $request->request->get('token');

                $d_data = $deviceModel->where('d_mac', $macid)->first();
                if (!$d_data) {
                    return -2; //无设备信息
                }
                $u_data = $updaterecord->where('u_mac', $macid)->where('u_chipid', $chipid)->where('u_oldversion', $oldversion)->where('u_newversion', $d_data->d_version)->first();
                if (!$u_data) {
                    return -2; //无更新请求记录
                }
                if ($u_data->status == 1) {
                    return -2; //更新状态：1不允许更新
                }
                $d_md5 = md5($u_data->u_id . $macid . $chipid . $oldversion . $u_data->u_newversion);

                if ($d_md5 != $token) {
                    return -2; //token错误
                }

                if ($request->request->has('status')){
                    $status = $request->request->get('status');
                    if ($status=="1"){
                        $a = $updaterecord->where("u_id",$u_data->u_id)->update(["status" => 1]);
                        if(!$a){
                            return -2; //设备端更新完成，但是服务器端更新设备更新状态失败
                        }
                        return 1; //设备端更新完成，服务器更新设备更新状态完成
                    }
                    return -2; //设备端未更新，不更新设备更新状态
                }
                $filename = 'YXCFmodels/' . $u_data->u_newversion . '.zip';
                $files = base_path($filename);

                if (!file_exists($files)) {
                    return -2; //服务器端更新文件不存在，返回更新失败
                }
                $name = basename($files);

                return response()->download($files, $name, $headers = ['Content-Type' => 'application/zip;charset=utf-8']); //返回更新压缩文件，设备端需解压到指定库文件夹
            }
        }else{
            return -2; //非法请求，请求未使用post方式
        }

    }

    public function deviceic(Request $request,DeviceicModel $deviceicModel){
//        硬加密访问接口，1、用于控制硬加密设备图像回传开关  2、用于硬加密设备计数统计

        if($request->isMethod("post")) {
            if($request->request->has("mac") and $request->request->has("version")){
                $request_mac = $request->request->get("mac");
                $request_version = $request->request->get("version");
                if($request_mac=="" or $request_version == ""){
                    return base64_decode(-2); // 数据内容为空
                }

                $deviceIC_data = $deviceicModel->where("deviceIc_mac",$request_mac)->first();
                if(!$deviceIC_data){
//                    $status = $deviceIcModel->create([
//                        'deviceIc_mac' => $request_mac,
//                        'deviceIc_version' => $request_version,
//                        'deviceIc_liveness'=>1,
//                        'deviceIc_collectStatus'=>1,
//                    ]);
//                    if(!$status){
//                        return base64_encode(-2); //请求失败  服务器操作失败
//                    }
//                    return base64_encode(1); //设备存在于数据库中，创建数据并返回关闭收集标识
                    return base64_encode(-2); //请求成功  但是无设备信息
                }else{
                    $deviceIc_data_collectStatus=$deviceIC_data->deviceIc_collectStatus;
                    $deviceIc_data_liveness=$deviceIC_data->deviceIc_liveness;
                    $deviceIc_data_liveness+=1;
                    $status = $deviceicModel->where("deviceIc_mac",$request_mac)->update([
                        "deviceIc_liveness" => $deviceIc_data_liveness,
                        "deviceIc_version"  => $request_version
                    ]);
                    if(!$status){
                        return base64_encode(-2); //请求失败  服务器操作失败
                    }
                    return base64_encode($deviceIc_data_collectStatus); // 请求成功，返回图片收集标识 1：关闭收集   2：打开收集
                }

            }else{
                return base64_encode(-2); //非法请求  接收到的请求内容不合要求
            }
        }else{
            return base64_encode(-2); //非法请求 接收到非post请求
        }
    }

    private static function authorization($data)
    {
        $data = $data . self::privEncrypt(md5($data), file_get_contents(base_path('keys/rsa_private_key.pem')));
        return $data;
    }

    private static function authorization5120($data)
    {
        $data = self::privEncrypt($data . md5($data), file_get_contents(base_path('keys/rsa_private_key_5120.pem')));
        $data = str_ireplace("+", "*", $data);
        return $data;
    }

    private static function getPrivateKey($privateKey)
    {
        return openssl_pkey_get_private($privateKey);
    }

    /**
     * 私钥加密
     */
    public static function privEncrypt($data, $privateKey)
    {
        if (!is_string($data)) {
            return null;
        }
        return openssl_private_encrypt($data, $encrypted, self::getPrivateKey($privateKey)) ? base64_encode($encrypted) : null;
    }

    /**
     * 私钥解密
     */
    public static function privDecrypt($encrypted, $privateKey)
    {
        if (!is_string($encrypted)) {
            return 0;
        }
//        return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey($privateKey)))? $decrypted : null;
        openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey($privateKey));
        return $decrypted;
    }

    public static function rsa_decode($data)
    {
        return self::privDecrypt($data, file_get_contents(base_path('keys/rsa_private_key.pem')));
    }

    public static function rsa_decode5120($data)
    {
        return self::privDecrypt($data, file_get_contents(base_path('keys/rsa_private_key_5120.pem')));
    }

    public function index2(Request $request, DeviceModel $deviceModel)
    {

        if(!$request->isMethod('post')){
            return base64_decode(-2);
        }
        if(!$request->has('info')){
            return base64_decode(-2);
        }
        $va =$request->request->get('info');
        $va = str_ireplace("*", "+", $va);
        if ($va == null and strlen($va) < 640) {
            return base64_encode(-2);//非法请求，请求内容长度超过预期
        }

        $val = self::rsa_decode5120($va);
        $vals = substr($val, 0, strlen($val) - 32);

        //明文比对
        if ($vals.md5($vals)!=$val) {
            return base64_encode(-2);//非法请求，明文与加密内容不符
        }
        $vals = json_decode($vals);

        if (!array_key_exists('id', (array)$vals) or!array_key_exists('mac', (array)$vals) or !array_key_exists('chipid', (array)$vals) or !array_key_exists('version', (array)$vals)) {
            return base64_encode(-2); //上传数有误，请求失败
        }

        $d_data = $deviceModel->where('d_did', $vals->id)->first();
        if($d_data == null){
            return base64_encode(-2);//请求失败，没有他的授权码id
        }
        if($d_data->d_mac==null or $d_data->d_chipid==null){ //设备激活流程，更新对应激活码设备信息
            $status = $deviceModel->where('d_did', $vals->id)->update([
                "d_mac" => $vals->mac,
                "d_chipid"  => $vals->chipid,
            ]);
            if(!$status){
                return base64_encode(-2);//服务器错误
            }
        }
        $d_data = $deviceModel->where('d_did', $vals->id)->first();
        if($d_data==null){
            return base64_decode(-2); //服务器错误
        }
        $livenessNum = $d_data->d_liveness;
        $livenessNum = (int)$livenessNum+1;
        $status = $deviceModel->where('d_did', $vals->id)->update(["d_liveness" => $livenessNum]);
        $data_req = "";
        if($d_data->status==1){
            if ($d_data->d_version == $vals->version) {
                if ($d_data->d_chipid != $vals->chipid or $d_data->d_mac!= $vals->mac) {
                    if(!array_key_exists('reserved', (array)$vals)){
                        $data_req = array('id'=>$vals->id,'mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => (string)strtotime($d_data->d_starttime), 'endtime' => (string)strtotime($d_data->d_endtime), 'version' => $vals->version,'status'=>"4");
                    }else{
                        $data_req = array('id'=>$vals->id,'mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => (string)strtotime($d_data->d_starttime), 'endtime' => (string)strtotime($d_data->d_endtime), 'version' => $vals->version,'status'=>"4",'reserved'=>$vals->reserved);
                    }
                    $data_req = json_encode($data_req);
                    return self::authorization5120($data_req); //chipid或mac错误

                }
                if(!array_key_exists('reserved', (array)$vals)){
                    $data_req = array('id'=>$vals->id,'mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => (string)strtotime($d_data->d_starttime), 'endtime' => (string)strtotime($d_data->d_endtime), 'version' => $vals->version,'status'=>"0");
                }else{
                    $data_req = array('id'=>$vals->id,'mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => (string)strtotime($d_data->d_starttime), 'endtime' => (string)strtotime($d_data->d_endtime), 'version' => $vals->version,'status'=>"0",'reserved'=>$vals->reserved);
                }
                $data_req = json_encode($data_req);
                return self::authorization5120($data_req); //设备授权成功，返回证书

            } else {
                if(!array_key_exists('reserved', (array)$vals)){
                    $data_req = array('id'=>$vals->id,'mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => (string)strtotime($d_data->d_starttime), 'endtime' => (string)strtotime($d_data->d_endtime), 'version' => $vals->version,'status'=>"1");
                }else{
                    $data_req = array('id'=>$vals->id,'mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => (string)strtotime($d_data->d_starttime), 'endtime' => (string)strtotime($d_data->d_endtime), 'version' => $vals->version,'status'=>"1",'reserved'=>$vals->reserved);
                }
                $data_req = json_encode($data_req);
                return self::authorization5120($data_req); //算法版本错误

            }
        }elseif ($d_data->status==2){
            if(!array_key_exists('reserved', (array)$vals)){
                $data_req = array('id'=>$vals->id,'mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => (string)strtotime($d_data->d_starttime), 'endtime' => (string)strtotime($d_data->d_endtime), 'version' => $vals->version,'status'=>"2");
            }else{
                $data_req = array('id'=>$vals->id,'mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => (string)strtotime($d_data->d_starttime), 'endtime' => (string)strtotime($d_data->d_endtime), 'version' => $vals->version,'status'=>"2",'reserved'=>$vals->reserved);
            }
            $data_req = json_encode($data_req);
            return self::authorization5120($data_req); //设备停用状态

        }elseif ($d_data->status==3){
            if(!array_key_exists('reserved', (array)$vals)){
                $data_req = array('id'=>$vals->id,'mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => (string)strtotime($d_data->d_starttime), 'endtime' => (string)strtotime($d_data->d_endtime), 'version' => $vals->version,'status'=>"3");
            }else{
                $data_req = array('id'=>$vals->id,'mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime' => (string)strtotime($d_data->d_starttime), 'endtime' => (string)strtotime($d_data->d_endtime), 'version' => $vals->version,'status'=>"3",'reserved'=>$vals->reserved);
            }
            $data_req = json_encode($data_req);
            return self::authorization5120($data_req); //设备删除状态
        }
    }
}

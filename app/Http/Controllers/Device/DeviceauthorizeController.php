<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\DeviceModel;
use App\Model\Updaterecord;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Input;

class DeviceauthorizeController extends Controller
{
    //
    public function index(Request $request, DeviceModel $deviceModel, Updaterecord $updaterecord)
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
            $newmiwen = str_ireplace(" ","+",$miwen);
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
                            if($data->d_chipid != $vals->chipid){
                                return 4;//chipid 不正确
                            }
                            $data = array('mac' => $vals->mac, 'chipid' => $vals->chipid, 'starttime'=>strtotime($data->d_starttime),'endtime'=>strtotime($data->d_endtime),'company' => $vals->company, 'productname' => $vals->productname, 'version' => $vals->version, 'name' => $vals->name, 'tel' => $vals->tel);
                            $data = json_encode($data);
                            return self::authorization($data);
                        } else {
                            $datau = $updaterecord->where('u_mac', $vals->mac)->where('u_chipid', $vals->chipid)->where('u_oldversion',$vals->version)->first();
                            if ($datau != null) {
                                if($datau->status == 0) {
                                    $token = md5($datau->u_id . $datau->u_mac . $datau->u_chipid . $datau->u_oldversion . $datau->u_newversion);
                                    return $token;
                                }else{
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
     * @param Updaterecord $updaterecord 更新记录
     * @param DeviceModel $deviceModel 设备库
     * @return int|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request, Updaterecord $updaterecord, DeviceModel $deviceModel)
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
                    return -2;
                }
                $u_data = $updaterecord->where('u_mac', $macid)->where('u_chipid', $chipid)->where('u_oldversion', $oldversion)->where('u_newversion', $d_data->d_version)->first();
                if (!$u_data) {
                    return -2;
                }
                if($u_data->status==1){
                    return -2;
                }
                $d_md5 = md5($u_data->u_id . $macid . $chipid . $oldversion . $u_data->u_newversion);
                if ($d_md5 != $token) {
                    return -2;
                }
                $filename = 'YXCFmodels/' . $u_data->u_newversion . '.zip';
                //            dd($macid);
                $files = base_path($filename);
                $name = basename($files);
                return response()->download($files,$name, $headers = ['Content-Type' => 'application/zip;charset=utf-8']);
            }
        }
        return -2;
    }

    private static function authorization($data)
    {
        $data = $data . self::privEncrypt(md5($data), file_get_contents(base_path('keys/rsa_private_key.pem')));
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
        // return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey($privateKey)))? $decrypted : null;
        openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey($privateKey));
        return $decrypted;
    }

    public static function rsa_decode($data)
    {
        return self::privDecrypt($data, file_get_contents(base_path('keys/rsa_private_key.pem')));
    }

}

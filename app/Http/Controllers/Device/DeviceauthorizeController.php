<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\DeviceModel;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Input;

class DeviceauthorizeController extends Controller
{
    //
    public function index(Request $request, DeviceModel $deviceModel)
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

            if ($va == null and strlen($va) < 173) {
                return -2;//非法请求
            }
            $val = self::rsa_decode(substr($va, -172, 172));
            $vals = substr($va, 0, strlen($va) - 172);
            if (md5($vals) != $val) {
                return -2;//非法请求
            }
            $vals = json_decode($vals);
            if (!array_key_exists('mac', (array)$vals) or !array_key_exists('cpuid', (array)$vals) or !array_key_exists('company', (array)$vals) or !array_key_exists('productname', (array)$vals) or !array_key_exists('version', (array)$vals) or !array_key_exists('name', (array)$vals) or !array_key_exists('tel', (array)$vals)) {
                return -2;
            }
            $data = $deviceModel->where('d_mac', $vals->mac)->first();
            date_default_timezone_set('PRC');//设置时区
            if ($data != null) {
                if ($data->status == 1) {
                    if (strtotime($data->d_starttime) < strtotime(date("Y-m-d H:i:s")) and strtotime(date("Y-m-d H:i:s")) < strtotime($data->d_endtime)) {
                        if ($data->d_version == $vals->version) {
                            $data = array('mac' => $vals->mac, 'cpuid' => $vals->cpuid, 'company' => $vals->company, 'productname' => $vals->productname, 'version' => $vals->version, 'name' => $vals->name, 'tel' => $vals->tel);
                            $data = json_encode($data);
                            return self::authorization($data);
                        } else {
                            return 0;//算法版本不正确
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

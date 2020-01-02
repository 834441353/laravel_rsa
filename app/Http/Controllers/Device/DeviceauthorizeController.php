<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use App\Model\DeviceModel;

// use Illuminate\Support\Facades\Input;

class DeviceauthorizeController extends Controller
{
    //
    public function index(Request $request,DeviceModel $deviceModel)
    {
        $val = '';
        $vals = '';
        if ($request->isMethod('post')){
            $val = self::rsa_decode($request->request->get('a'));
            $vals = json_decode($val);

            // $data = $deviceModel->where('d_mac',$vals->mac)->where('d_cpuid',$vals->cpuid)->firstOrFail();
            $data = $deviceModel->where('d_mac',$vals->mac)->where('d_cpuid',$vals->cpuid)->get();
            dd($data);
            if($data != null){
                echo 1;
                return 1;
            }else{
                echo 2;
                return 0;
            }
        }else{
            echo 3;
            return 0;
        }
//        $d_cpuid = DB::select("select * from yx_device where d_mac = ?",["34-97-F6-8B-E4-26"]);
//        var_dump($d_cpuid->d_cpuid);
//        return $d_cpuid;
        // $data['deviceInfo'] = $deviceModel->where('d_mac',"34-97-F6-8B-E4-26")->get();
        // $data['deviceInfo'] = $deviceModel->get();
        

        // return $vals->id;
        // return view('welcome');
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

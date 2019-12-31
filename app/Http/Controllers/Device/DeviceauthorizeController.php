<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Input;

class DeviceauthorizeController extends Controller
{
    //
    public function index(Request $request){
    	$val =  self::rsa_decode($request->request->get('a'));
    	return $val;
    	// return view('welcome');
    }

    private static function getPrivateKey($privateKey){
    	return openssl_pkey_get_private($privateKey);
  	}
  	/**
    * 私钥加密
    */
  	public static function privEncrypt($data,$privateKey){
	    if(!is_string($data)){
	      return null;
	    }
	    return openssl_private_encrypt($data,$encrypted,self::getPrivateKey($privateKey))? base64_encode($encrypted) : null;
  	}

  	/**
    * 私钥解密
    */
  	public static function privDecrypt($encrypted,$privateKey){
	    if(!is_string($encrypted)){
	      return 0;
	    }
	    // return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey($privateKey)))? $decrypted : null;
	    openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey($privateKey));
	    return $decrypted;
  	}

  	public static function rsa_decode($data){
	    return self::privDecrypt($data ,file_get_contents(base_path('keys/rsa_private_key.pem')));
  	}

}
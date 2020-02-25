<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
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

    public function tableshow(DeviceModel $deviceModel,CompanyModel $companyModel){
        $data = $companyModel->paginate(13);
        return view('home.tableshow',compact('data'));
    }

    public function showCompany(Request $request,CompanyModel $companyModel,DeviceModel $deviceModel){
        $id = $request->id;
        $data = $deviceModel->where("d_companyid",$id)->paginate(13);
//        $data['companyname'] = $companyModel->where("c_id",$id)->first()->c_companyname;
//        dd($data[0]);
        if($data->count()==0){
            $data = $companyModel->paginate(13);
            return view('home.tableshow',compact('data'));
        }
        for ($item=0;$item<$data->count();$item++){
//            var_dump($data[$item]['d_endtime']);
            if(strtotime($data[$item]['d_starttime'])>strtotime(date('Y-m-d H:i:s'))){
//                $data[$item]['t'] = $data[$item]['d_starttime'];
                $data[$item]['t'] = '未开始';
            }else{
                $t = round((strtotime($data[$item]['d_endtime'])-strtotime(date('Y-m-d H:i:s')))/86400,0);
                if($data[$item]['status']==1){
                    if($t<0){
                        $data[$item]['status'] = 4;
                    }
                }
                $data[$item]['t'] = $t;
            }
        }
        return view('home.companyDTshow',compact('data'));
    }

    public function edita(Request $request,DeviceModel $deviceModel,CompanyModel $companyModel){

        if ($request->isMethod("get")){
            $id = $request->id;
            $data = $deviceModel->find($id);
            $data_company = $companyModel->get();
//            dd($data);
            return view('home.editItem',["data"=>$data,"data_company"=>$data_company]);
        }elseif ($request->isMethod("post")){
            $data = $request->request->all();
//            dd($data);
            $check= [
                'd_cpuid'=>'required',
                'd_version'=>'required',
                'd_productname'=>'required',
                'd_name'=>'required',
                'd_tel'=>'required',
            ];
            $messages= [
                'd_cpuid.required' => 'cpuid不能为空！',
                'd_version.required'=>'算法版本不能为空！',
                'd_productname.required'=>'产品名不能为空！',
                'd_name.required'=>'紧急联系人不能为空！',
                'd_tel.required'=>'紧急联系人电话不能为空！',
            ];
            $status = Validator::make($data,$check,$messages);
            if($status->fails()){
                return ['status'=>false,'message'=>$status->messages()->all()];
            }
            $status = $deviceModel->where("d_id",$data['d_id'])->update(['d_cpuid'=>$data['d_cpuid'],'d_starttime'=>$data['d_starttime'],'d_endtime'=>$data['d_endtime'],'d_companyid'=>$data['d_companyid'],'d_productname'=>$data['d_productname'],'d_version'=>$data['d_version'],'d_name'=>$data['d_name'],'d_tel'=>$data['d_tel'],'status'=>$data['status']]);
            if ($status){
                return ['status'=>true,'message'=>['添加成功！']];
            }else{
                return ['status'=>false,'message'=>['添加失败！']];
            }
        }
    }

    public function dela(Request $request,DeviceModel $deviceModel){
        if ($request->isMethod('post')){
            if($request->request->has('id')){
                $val = $request->request->get('id');
                $status = $deviceModel->where("d_id",$val)->update(["status"=>3]);
                if ($status){
                    return ['status'=>true,'message'=>$status];
                }else{
                    return ['status'=>false,'message'=>0];
                }

            }
            return ['status'=>false,'message'=>0];
        }
        return 22;
    }

    public function addDevice(Request $request,DeviceModel $deviceModel,CompanyModel $companyModel){
        if($request->isMethod('get')){
            $data_company = $companyModel->get();
            return view('home.addDevice',["data_company"=>$data_company]);
        }elseif ($request->isMethod("post")){
            $data = $request->request->all();
//            dd($data);
            $check= [
                'd_mac'=>'required',
                'd_cpuid'=>'required',
                'd_version'=>'required',
                'd_productname'=>'required',
                'd_name'=>'required',
                'd_tel'=>'required',
            ];
            $messages= [
                'd_mac.required'=>'Mac地址不能为空',
                'd_cpuid.required' => 'cpuid不能为空！',
                'd_version.required'=>'算法版本不能为空！',
                'd_productname.required'=>'产品名不能为空！',
                'd_name.required'=>'紧急联系人不能为空！',
                'd_tel.required'=>'紧急联系人电话不能为空！',
            ];
            $status = Validator::make($data,$check,$messages);
            if($status->fails()){
                return ['status'=>false,'message'=>$status->messages()->all()];
            }
            $status = $deviceModel->where('d_mac',$data['d_mac'])->first();
            if($status != null){
                return ['status'=>false,'message'=>['该设备已存在！']];
            }
//            var_dump($data['d_cpuid']);
            $status = $deviceModel->create(['d_mac'=>$data['d_mac'],'d_starttime'=>$data['d_starttime'],'d_cpuid'=>$data['d_cpuid'],'d_endtime'=>$data['d_endtime'],'d_companyid'=>$data['d_companyid'],'d_productname'=>$data['d_productname'],'d_version'=>$data['d_version'],'d_name'=>$data['d_name'],'d_tel'=>$data['d_tel'],'status'=>$data['status']]);
            if ($status){
                return ['status'=>true,'message'=>['添加成功！']];
            }else{
                return ['status'=>false,'message'=>['添加失败！']];
            }
        }
        return view('home.additem');
    }

    public function addCompany(Request $request,CompanyModel $companyModel){
        if($request->isMethod('get')) {
            return view('home.addCompany');
        }elseif ($request->isMethod('post')){
            $data = $request->request->all();
            $status = $companyModel->where('c_companyname',$data['c_companyname'])->first();
            if($status != null){
                return ['status'=>false,'message'=>['该公司已存在！']];
            }
            $status = $companyModel->create(['c_companyname'=>$data['c_companyname'],'comment'=>$data['comment']]);
            if ($status){
                return ['status'=>true,'message'=>['添加成功！']];
            }else{
                return ['status'=>false,'message'=>['添加失败！']];
            }
        }
    }

    public function imgRecevice(Request $request){
        if ($request->isMethod('post')) {
//            return $request->request->all();
            if (!$request->request->has('info')) {
                return -2;//非法请求
            }
            $id = $request->request->get('info');
            $file = $request->file('file');//获取文件
            if(!$file){
                return -3;//非法请求
            }
//            $fileName = md5(time() . rand(0, 10000)) . '.' . $file->getClientOriginalName();//随机名称+获取客户的原始名称
            $fileName = $file->getClientOriginalName();
//            $savePath = $id.'/'.strtotime(date("Y-m-d H:i:s")).'_'.$fileName;
            $savePath = $id.'/'.md5(time() . rand(0, 10000)).'_'.$fileName;
            $status = Storage::put($savePath, File::get($file));//通过Storage put方法存储   File::get获取到的是文件内容
            if(!$status){
                return -1;//服务器错误
            }
            return 1;
        }
        return -2;//非法请求
    }

    public function importexcel(DeviceModel $deviceModel){
        return view('home.importexcel');
    }
}

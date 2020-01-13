<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\AdminModel;
use Illuminate\Http\Request;
use Validator;

class IndexController extends Controller
{
    //
    public function index()
    {
        return view('welcome');
    }

    public function login(Request $request, AdminModel $adminModel)
    {
        if ($request->isMethod('post')) {
            $data = $request->request->all();
//            dd($data);
            $check = [
                'a_username' => 'required',
                'a_password' => 'required',

            ];
            $messages = [
                'a_username.required' => '账号不能为空！',
                'a_password.required' => '密码不能为空！',
            ];

            $status = Validator::make($data, $check, $messages);
            if ($status->fails()) {
                return redirect()->back()->withErrors($status->messages());
            }

            $where = [
                ['a_username', '=', $data['a_username']],
                ['a_password', '=', $data['a_password']],
            ];
            $admin = $adminModel->where($where)->first();
            if (!$admin) {
                return redirect()->back()->withErrors("账号或者密码有误1！");
            }
//            $status = \Hash::check($data['a_password'], $admin->password);
//            if(!$status){
//                return redirect()->back()->withErrors("账号或者密码有误2！");
//            }
            $request->session()->put('is_login',1);
            $request->session()->put('a_id',$admin->a_id);

            return redirect()->to('tableshow');


        }
        return view('admin.login');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('is_login');
        $request->session()->forget('a_id');
        return view('admin.login');
    }
}

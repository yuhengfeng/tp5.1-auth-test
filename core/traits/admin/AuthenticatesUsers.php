<?php

/**
 * Describe: 登陆
 * Created on: 2018/5/22/022 13:23
 * Created by Henry
 */
namespace core\traits\admin;

use app\admin\model\Administrator;
use core\facades\Flash;
use think\facade\Session;
use think\facade\Validate;
use think\Request;

Trait AuthenticatesUsers
{
    /**
     * @var string
     */
    protected $redirectTo = '/admin';
    /**
     * @return \think\response\View
     */
    public function showLoginForm()
    {
        return view('auth/login');
    }

    /**
     * @param Request $request
     * @return $this|\think\response\Redirect
     * 登陆请求
     */
    public function login(Request $request)
    {
        $validate = $this->validateLogin($request);

        if ($validate !== null){
            Flash::error($validate);
            return redirect('auth/login');
        }

        if (!$this->attemptLogin($request)) {
            Flash::error('账号或密码错误');
            return redirect('auth/login');
        }
        $this->saveLoginMessage();

        return redirect($this->redirectTo);
    }

    /**
     * 设置session
     */
    protected function saveLoginMessage()
    {
        $adminId = Administrator::where('name',input('name'))->value('id');

        return Session::set('admin_auth',$adminId);
    }
    /**
     * @param Request $request
     * @return mixed
     * 登陆验证
     */
    protected function validateLogin(Request $request)
    {
        $rule = [
            'name'  => 'require|max:25|token',
            'password'  => 'require|min:6|max:125',
        ];

        $msg = [
            'name.require' => '用户名必填',
            'name.token' => '不能重复提交',
            'password.require' => '密码必填',
            'name.max'     => '用户名最多不能超过25个字符',
            'password.min'     => '密码不少于6个字符',
            'password.max'     => '密码不能超过125个字符',
        ];

        $validate = Validate::make($rule)->message($msg);
        $result = $validate->scene('login')->check($request->param());

        if(!$result){
            return $validate->getError();
        }
    }

    /**
     * @return bool
     * 密码校验
     */
    protected function attemptLogin()
    {
        $user = Administrator::getByName(input('name'));
        if (isset($user) && $user !== null){
            return password_verify(input('password'),$user['password']);
        }else{
            return false;
        }
    }

    /**
     * @return \think\response\Redirect
     * 退出
     */
    public function logout()
    {
        Session::delete('admin_auth');

        Flash::success('退出成功');

        return redirect('auth/login');
    }
}
<?php
/**
 * UserValidate.php
 * Describe:
 * Created on: 2018/5/22/022 15:54
 * Created by Henry
 */
namespace app\admin\validate;

use think\Validate;

class AdminUserValidate extends Validate
{
    protected $rule = [
        'name'  => 'require|length:4,25',
        'password'  => 'require|min:6',
        'role_ids' => 'require',
        'confirm_password'  => 'require|min:6|confirm:password',
        'email' => 'email',
    ];

    protected $message = [
        'name.require' => '用户名必填',
        'role_ids.require' => '角色至少选一个',
        'name.length'     => '用户名必须在4-25个字符之间',
        'password.require' => '密码必填',
        'password.min'     => '密码长度必须超过6位',
        'confirm_password.require' => '确认密码必填',
        'confirm_password.min'     => '确认密码长度必须超过6位',
        'confirm_password.confirm'     => '确认密码与密码必须一致',
        'email.email'        => '邮箱格式错误',
    ];

    // 应用场景
    protected $scene = [
        'add'   =>  ['name','password','email','confirm_password','role_ids'],
        'edit'  =>  ['name','password','email','confirm_password','role_ids'],
    ];

    /**
     * @return $this
     * 登录场景
     */
    public function sceneLogin()
    {
        return $this->only(['name','password'])
            ->remove('name', ['length:4,25'])
            ->remove('password', ['min:6']);
    }
    /**
     * @return $this
     * 设置账户场景
     */
    public function sceneAccount()
    {
        return $this->only(['name','email','password','confirm_password'])
            ->remove('confirm_password', ['require'])
            ->remove('password', ['require']);

    }
}
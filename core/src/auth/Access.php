<?php
namespace core\src\auth;

use app\admin\model\Administrator;
use core\contracts\Guard;
use core\exception\ErrorException;
use core\facades\Auth;
use core\src\auth\access\AuthorizationException;
use think\facade\Session;

class Access implements Guard
{
    protected $adminId;

    public function __construct()
    {
        if (!$this->check()){
            return false;
        }
        $this->adminId = Session::get('admin_auth');
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return Session::has('admin_auth');
    }

    /**
     * Get the currently authenticated user.
     *
     */
    public function user()
    {
        return Administrator::findOrFail($this->adminId);
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        return $this->adminId;
    }
    /***
     * @param $name
     * @return mixed
     */
    public function isRole($name)
    {
        if (is_null($name)){
            throw new ErrorException('缺少参数!');
        }
        $roles = app('app\admin\model\Administrator')->get($this->adminId)->roles;
        $roles = array_column(json_decode($roles,true),'slug');
        return in_array($name,$roles);
    }

    /**
     * @return bool
     * 是否为超管
     */
    public function isAdmin()
    {
        $adminInfo = $this->user();

        return isset($adminInfo['is_admin']) && $adminInfo['is_admin'] ==1;
    }
}
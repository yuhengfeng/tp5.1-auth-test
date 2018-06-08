<?php
namespace core\facades;

use think\Facade;
/**
 * @see Guard
 * @mixin \think\Cache
 * @method bool check()  判断是否登陆
 * @method string user()  读取已认证的用户名
 * @method string id()  读取已认证的用户id
 */
class Auth extends Facade
{
    protected static function getFacadeClass()
    {
        return 'core\contracts\Guard';
    }
}
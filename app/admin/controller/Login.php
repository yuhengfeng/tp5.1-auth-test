<?php
/**
 * Login.php
 * Describe:
 * Created on: 2018/5/22/022 13:23
 * Created by Henry
 */
namespace app\admin\controller;

use core\traits\admin\AuthenticatesUsers;
use think\Controller;

class Login extends Controller
{
    use AuthenticatesUsers;

}

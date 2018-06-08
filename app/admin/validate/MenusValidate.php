<?php
/**
 * UserValidate.php
 * Describe:
 * Created on: 2018/5/22/022 15:54
 * Created by Henry
 */
namespace app\admin\validate;

use think\Validate;

class MenusValidate extends Validate
{
    protected $rule = [
        'name'  => 'require|max:40',
    ];

    protected $message = [
        'name.require' => '菜单名称必填',
        'name.max'     => '菜单名称不能大于40个字符',
    ];
}
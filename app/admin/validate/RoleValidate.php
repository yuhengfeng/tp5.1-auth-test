<?php
/**
 * UserValidate.php
 * Describe:
 * Created on: 2018/5/22/022 15:54
 * Created by Henry
 */
namespace app\admin\validate;

use think\Validate;

class RoleValidate extends Validate
{
    protected $rule = [
        'name'  => 'require|max:40',
    ];

    protected $message = [
        'name.require' => '角色名称必填',
        'name.max'     => '角色名称不能大于50个字符',
    ];
}
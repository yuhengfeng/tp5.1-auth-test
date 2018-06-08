<?php

namespace app\admin\model;

use app\admin\model\traits\AdminUserTrait;
use think\Model;
use think\model\concern\SoftDelete;

class Administrator extends Model
{
    use AdminUserTrait;
    use SoftDelete;

    protected $table = 'admin';
    //自动填入创建|修改时间
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';

    /**
     * @param $value
     * @return string
     */
    public function setRoleIdsAttr($value)
    {
        return implode(',',$value);
    }

    public function roles()
    {
        return $this->belongsToMany('Role','AdminRoles','role_id','admin_id');
    }
}

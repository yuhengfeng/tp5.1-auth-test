<?php

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class AdminRoles extends Model
{
    protected $table = 'admin_roles';
    //自动填入创建|修改时间
    protected $autoWriteTimestamp = true;

    public function getAdminRoles($id)
    {
        return $this->where('admin_id',$id)->column('role_id');
    }
}

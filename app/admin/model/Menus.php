<?php

namespace app\admin\model;

use app\admin\model\traits\AdminMenusTrait;
use think\Model;
use think\model\concern\SoftDelete;

class Menus extends Model
{
    use AdminMenusTrait;
    use SoftDelete;

    protected $table = 'menus';
    //自动填入创建|修改时间
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';

    public function roles()
    {
        return $this->belongsToMany('Role','RoleMenus','role_id','menu_id');
    }
}

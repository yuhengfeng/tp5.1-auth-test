<?php

namespace app\admin\model;

use app\admin\model\traits\AdminMenusTrait;
use think\Model;
use think\model\concern\SoftDelete;

class Role extends Model
{
    use AdminMenusTrait;
    use SoftDelete;

    protected $table = 'role';
    //自动填入创建|修改时间
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';

    public function menus()
    {
        return $this->belongsToMany('Menus','RoleMenus','menu_id','role_id');
    }

}

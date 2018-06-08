<?php

namespace app\admin\model;

use think\Model;

class RoleMenus extends Model
{
    protected $table = 'role_menus';
    //自动填入创建|修改时间
    protected $autoWriteTimestamp = true;

    /**
     * @param $id
     * @return array
     */
    public function getRoleMenus($id)
    {
        return $this->where('menu_id',$id)->column('id','role_id','menu_id');
    }
}

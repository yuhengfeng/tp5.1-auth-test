<?php
namespace app\admin\model\traits;

trait AdminMenusTrait
{
    /**
     * @param $query
     * @return mixed
     * 列表字段
     */
    public function scopeFields($query)
    {
        return $query->field('id,name,parent_id,header,icon,url,status,sort');
    }

    /**
     * @param $query
     * @return mixed
     * 菜单倒叙
     */
    public function scopeCurrent($query)
    {
        return $query->order('sort','desc');
    }

    public function scopeStatus($query)
    {
        return $query->where('status','1');
    }
    public function scopeWithSelect($query)
    {
        return $query->value('avatar');
    }

    /**
     * @param $query
     * @return mixed
     * 左部导航
     */
    public function scopeMenusNav($query)
    {
        return $query->where('menu_type',1);
    }
}

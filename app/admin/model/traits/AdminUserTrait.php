<?php
namespace app\admin\model\traits;

trait AdminUserTrait
{
    // 定义全局的查询范围
//    protected function base($query)
//    {
//        $query->where('status',1);
//    }
    public function scopeUser($query,$id)
    {
        return $query->where('id',$id);
    }
    public function scopeAvatar($query)
    {
        return $query->value('avatar');
    }

    /**
     * @param $query
     * @return mixed
     * 超管
     */
    public function scopeIsAdmin($query)
    {
        return $query->where('is_admin',1);
    }
}

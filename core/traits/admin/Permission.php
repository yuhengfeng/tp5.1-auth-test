<?php
namespace core\traits\admin;

use app\admin\model\Menus;
use core\facades\Auth;
use think\Db;

trait Permission
{
    /**
     * @return array|\PDOStatement|string|\think\Collection
     * 获取当前角色的菜单功能
     */
    protected function getAllowMenusAction($type = '')
    {
        $controller = request()->controller();
        if (Auth::isAdmin())
        {
            switch ($type)
            {
                //菜单导航
                case 'menus_nav':
                    $where = [
                        'menu_type' => 0,
                        'status' =>1
                    ];
                    break;
                //左部功能
                case 'menus_left_action':
                    $where = [
                        'menu_type' => 1,
                        'c' => $controller,
                        'status' =>1
                    ];
                    break;
                //右部特殊功能
                case 'menus_right_action':
                    $where = [
                        'menu_type' => 2,
                        'c' => $controller,
                        'status' =>1
                    ];
                    break;
                // 列表功能
                case 'menus_list_action':
                    $where = [
                        'menu_type' => 3,
                        'c' => $controller,
                        'status' =>1
                    ];
                    break;
                default :
                    $where = [
                        'status' =>1
                    ];
            }
            return $this->getAdminMenusAction($where);
        }else{
            switch ($type)
            {
                //菜单导航
                case 'menus_nav':
                    $where = [
                        'm.menu_type' => 0,
                        'm.status' =>1
                    ];
                    break;
                //左部功能
                case 'menus_left_action':
                    $where = [
                        'm.menu_type' => 1,
                        'm.status' =>1,
                        'm.c' => $controller,
                    ];
                    break;
                //右部特殊功能
                case 'menus_right_action':
                    $where = [
                        'm.menu_type' => 2,
                        'm.status' =>1,
                        'm.c' => $controller,
                    ];
                    break;
                // 列表功能
                case 'menus_list_action':
                    $where = [
                        'm.menu_type' => 3,
                        'm.status' =>1,
                        'm.c' => $controller,
                    ];
                    break;
                default :
                    $where = [
                        'm.status' =>1
                    ];
            }
            return $this->getAllowMenusSql($where);
        }
    }

    /**
     * @return mixed
     * 获取当前的菜单id
     */
    final function getMenusId($key)
    {
        return Menus::where('url',request()->path())->value($key);
    }


    /**
     * @param $where
     * @return array|\PDOStatement|string|\think\Collection
     * 获取超级管理员的所有菜单
     */
    protected function getAdminMenusAction($where)
    {
        return Db::table('menus')
            ->field('id,name,parent_id,header,icon,url,status,sort,style,slug,c')
            ->where('delete_time','null')
            ->where($where)
            ->order('sort','desc')
            ->select();
    }
    /**
     * @param $where
     * @return array|\PDOStatement|string|\think\Collection
     * 当前角色
     */
    protected function getAllowMenusSql($where)
    {
        return Db::table('menus')
            ->alias('m')
            ->field('m.id,m.name,m.parent_id,m.header,m.icon,m.url,m.status,m.sort,m.style,m.c,m.slug')
            ->join('role_menus rm','m.id = rm.menu_id')
            ->join('role r','r.id = rm.role_id')
            ->join('admin_roles ar','r.id = ar.role_id')
            ->join('admin a','a.id = ar.admin_id')
            ->where('m.delete_time','null')
            ->where('r.delete_time','null')
            ->where('a.delete_time','null')
            ->where($where)
            ->where('a.id',Auth::id())
            ->order('m.sort','desc')
            ->group('m.id')
            ->select();
    }
}
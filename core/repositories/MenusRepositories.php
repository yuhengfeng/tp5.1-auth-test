<?php
namespace core\repositories;

use app\admin\model\Menus;
use app\admin\model\RoleMenus;
use think\Db;

class MenusRepositories
{
    /**
     * @var Menus
     */
    protected $adminMenus;
    protected $roleMenus;
    /**
     * MenusRepositories constructor.
     * @param Menus $adminMenus
     */
    public function __construct(Menus $adminMenus,RoleMenus $roleMenus)
    {
        $this->adminMenus = $adminMenus;
        $this->roleMenus = $roleMenus;
    }

    /**
     * @return mixed
     * 获取所有菜单
     */
    public function getAll()
    {
        return $this->adminMenus->fields()->status()->paginate(config('view.paginate'));
    }
    /**
     * @return array
     * 获取父节点菜单
     */
    public function getMenusSelect()
    {
        $data =  $this->adminMenus->field('id,parent_id,name')->select();
        return get_category_tree($data);
    }

    /**
     * @param $request
     * 新增菜单
     */
    public function create($request)
    {
        $request['admin_id'] = 1;
        $request['status'] = isset($request['status']) ? $request['status'] : 1;
        if (count(explode('/',$request['url'])) == 3){
            list($m,$c,$a) = explode('/',$request['url']);
            $request['m'] = strtoupper($m);
            $request['c'] = strtoupper($c);
            $request['a'] = strtoupper($a);
        }
        return $this->adminMenus->create($request);
    }
    /**
     * @param $request
     * @return static
     * 编辑 菜单
     */
    public function update($request,$id)
    {
        $request['admin_id'] = 1;
        $request['status'] = isset($request['status']) ? $request['status'] : 1;
        if (count(explode('/',$request['url'])) == 3){
            list($m,$c,$a) = explode('/',$request['url']);
            $request['m'] = strtoupper($m);
            $request['c'] = strtoupper($c);
            $request['a'] = strtoupper($a);
        }
        $arr = [];
        foreach ($request as $key=>$value){
            if ($key != 'role_id'){
                $arr[$key] = $value;
            }
        }
        $this->adminMenus->where('id',$id)->update($arr);
    }

    /**
     * @param $id
     * @return int
     * 删除菜单
     */
    public function delete($id)
    {
        Db::transaction(function ()use($id){
            $this->adminMenus->destroy($id,true);
            return $this->roleMenus->where('menu_id',$id)->delete();
        });
    }
}
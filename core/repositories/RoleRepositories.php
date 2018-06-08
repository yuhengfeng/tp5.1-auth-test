<?php
namespace core\repositories;


use app\admin\model\AdminRoles;
use app\admin\model\Menus;
use app\admin\model\Role;
use app\admin\model\RoleMenus;
use think\Db;

class RoleRepositories
{
    /**
     * @var Role
     */
    protected $role;
    protected $roleMenus;

    /**
     * MenusRepositories constructor.
     * @param Role $adminMenus
     */
    public function __construct(Role $role,RoleMenus $roleMenus)
    {
        $this->role = $role;
        $this->roleMenus = $roleMenus;
    }

    /**
     * @return mixed
     * 获取所有菜单
     */
    public function getAll()
    {
        return $this->role->paginate(config('view.paginate'));
    }
    /**
     * @return array
     * 获取父节点角色
     */
    public function getRoleSelect()
    {
        return $this->role->field('id,parent_id,name')->select()->toArray();
    }

    /**
     * @param $request
     * 新增角色 |分配权限
     */
    public function create($request)
    {
        Db::transaction(function () use ($request){
            $roles = $this->role->create([
                'name' => $request['name'],
                'parent_id' => $request['parent_id'],
                'sort' => $request['sort'],
                'desc' => $request['desc'],
                'access_id' => implode(',',$request['menu_id'])
            ]);
            $roleMenus = array ();
            if(isset($request['menu_id'])){
                foreach ($request['menu_id'] as $val){
                    $roleMenus[] = [
                        'menu_id' => $val,
                        'role_id' => $roles['id']
                    ];
                }
            }
            return $this->roleMenus->saveAll($roleMenus);
        });
    }

    /**
     * @param $request
     * @return static
     * 编辑 角色 |权限
     */
    public function update($request,$id)
    {
        Db::transaction(function () use ($request,$id){
            $roles = $this->role->update([
                'id' => $id,
                'name' => $request['name'],
                'parent_id' => $request['parent_id'],
                'sort' => $request['sort'],
                'desc' => $request['desc'],
                'access_id' => isset($request['menu_id']) ? implode(',',$request['menu_id']) : ''
            ]);
            $roleMenus = array ();
            $this->roleMenus->where('role_id',$id)->delete();
            if(isset($request['menu_id'])){
                foreach ($request['menu_id'] as $val){
                    $roleMenus[] = [
                        'menu_id' => $val,
                        'role_id' => $id
                    ];
                }
                $this->roleMenus->saveAll($roleMenus);
            }
            return $roles;
        });
    }
    /**
     * @param $id
     * @return int
     * 删除角色
     */
    public function delete($id)
    {
        Db::transaction(function ()use($id){
            $roleInfo = $this->role->findOrFail($id);
            $menu_ids = explode(',',$roleInfo['access_id']);
            $this->role->destroy($id,true);
            return $roleInfo->menus()->detach($menu_ids);
        });
    }
}
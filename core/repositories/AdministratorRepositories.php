<?php
namespace core\repositories;

use app\admin\model\Administrator as Admin;
use app\admin\model\AdminRoles;
use think\Db;

class AdministratorRepositories
{
    protected $adminUser;
    protected $adminRoles;

    public function __construct(Admin $adminUser,AdminRoles $adminRoles)
    {
        $this->adminUser = $adminUser;
        $this->adminRoles = $adminRoles;
    }
    public function getAll()
    {
        return $this->adminUser->field('id,name,password,email,status,create_time,role_ids,is_admin')->paginate(config('view.paginate'));
    }

    /**
     * @param $request
     * @return static
     * 新增 |分配角色 管理员
     */
    public function create($request)
    {
        Db::transaction(function () use ($request){
            $file = request()->file('avatar');
            if ($file)
            {
                $file = app('core\handler\UploadImageHandler')->upload($file);
                $request['avatar'] = $file['filename'];
            }
            $request['password'] = bcrypt($request['password']);
            $adminInfo = $this->adminUser->create($request);
            $res = '';
            foreach ($request['role_ids'] as $id)
            {
                $res.= $adminInfo->roles()->attach($id,['create_time' => time()]);
            }
            return $res;
        });
    }

    /**
     * @param $request
     * @param $id
     * @return static
     * 更新管理员及对应的角色
     */
    public function update($request,$id)
    {
        Db::transaction(function ()use($request,$id){
            $upload = app('core\handler\UploadImageHandler');
            $avatar = request()->file('avatar');
            $admin = $this->adminUser->get($id);
            if (!$avatar && $avatar == null){
                $request['avatar'] = $admin['avatar'];
            }else{
//            $upload->clearOldImage(public_path().$avatarPath);
                $file = $upload->upload($avatar);
                $request['avatar'] = $file['filename'];
            }
            if ($request['password'] == '')
            {
                unset($request['password']);
            }else{
                $request['password'] == $admin['password'] ? $request['password'] = $admin['password'] : $request['password'] = bcrypt($request['password']);
            }
            $admin = $this->adminUser->save($request,['id'=>$id]);
            $roles = array ();
            $this->adminRoles->where('admin_id',$id)->delete();
            if(isset($request['role_ids'])){
                foreach ($request['role_ids'] as $val){
                    $roles[] = [
                        'admin_id' => $id,
                        'role_id' => $val
                    ];
                }
                $this->adminRoles->saveAll($roles);
            }
            return $admin;
        });
    }

    /**
     * @param $id
     * @return int
     * 删除管理员
     */
    public function delete($id)
    {
        Db::transaction(function ()use($id){
            $adminInfo = $this->adminUser->findOrFail($id);
            $role_ids = explode(',',$adminInfo['role_ids']);
            $this->adminUser->destroy($id,true);
            return $adminInfo->roles()->detach($role_ids);
        });
    }
}
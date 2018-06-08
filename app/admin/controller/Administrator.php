<?php
/**
 * @author henry
 */
namespace app\admin\controller;

use app\admin\model\Administrator as Admin;
use app\admin\model\AdminRoles;
use app\admin\validate\AdminUserValidate;
use core\ControllerBase;
use core\facades\Auth;
use core\facades\Flash;
use core\repositories\AdministratorRepositories;
use core\repositories\RoleRepositories;

class Administrator extends ControllerBase
{
    protected $administratorRepositories;
    protected $roleRepositories;

    public function __construct(AdministratorRepositories $administratorRepositories,RoleRepositories $roleRepositories)
    {
        parent::__construct();
        $this->administratorRepositories = $administratorRepositories;
        $this->roleRepositories = $roleRepositories;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function show()
    {
        $adminInfo = $this->administratorRepositories->getAll();
        $this->contentHeader('管理员','列表');
        $this->message([
            '<span class="label label-sm label-danger">删除操作请谨慎处理！</span>删除管理员会将该管理员对应的角色进行删除',
            '<span class="label label-sm label-primary">暂时没有回收站！</span>'
        ]);
        $this->assign('total',$adminInfo->total());
        $this->assign('pageRender',$adminInfo->render());
        $this->assign('admins',$adminInfo);

        return $this->fetch();
    }

    /**
     * @return mixed|string
     * 管理员账户信息
     */
    public function account(AdminUserValidate $request)
    {
        if (IS_POST)
        {
            if (!$request->scene('account')->check($this->request->except('_token_')))
            {
                Flash::error($request->getError());
                return redirect('admin/administrator/account');
            }
            $this->administratorRepositories->update($this->request->except('__token__,confirm_password,new_password'),Auth::id());
            Flash::success('更新成功');
            return redirect('admin/administrator/account');
        }else{
            $this->contentHeader('账户','信息');
            return $this->fetch();
        }
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(AdminUserValidate $request)
    {
        if (IS_POST)
        {
            $this->store($request);
        }else{
            $this->contentHeader('管理员','创建');

            $this->assign('roles',get_category_tree($this->roleRepositories->getRoleSelect()));

            return $this->fetch();
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function store(AdminUserValidate $request)
    {
        if (!$request->scene('add')->check($this->request->except('_token_')))
        {
            Flash::error($request->getError());
            return redirect('admin/administrator/create');
        }
        $this->administratorRepositories->create($this->request->except('__token__,confirm_password'));
        Flash::success('新增成功');
        return redirect('admin/administrator/create');
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id,AdminRoles $adminRoles,AdminUserValidate $request)
    {
        if (IS_POST)
        {
            $this->update($request,$id);
        }else{
            $this->contentHeader('管理员','编辑');
            $adminInfo = Admin::findOrFail($id);
            $this->assign('admins',$adminInfo);
            $this->assign('adminRole',$adminRoles->getAdminRoles($id));
            $this->assign('roles',get_category_tree($this->roleRepositories->getRoleSelect()));
            return $this->fetch();
        }
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(AdminUserValidate $request,$id)
    {

        if (!$request->scene('edit')->check($this->request->except('_token_')))
        {
            Flash::error($request->getError());
            return redirect('admin.manager.edit',['id'=>$id]);
        }
        $this->administratorRepositories->update($this->request->except('__token__,confirm_password'),$id);
        Flash::success('编辑成功');
        return redirect('admin/administrator/edit',['id'=>$id]);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $this->administratorRepositories->delete($id);
        Flash::success('删除成功');
        return redirect('admin/administrator/list');
    }
}

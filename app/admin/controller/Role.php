<?php

namespace app\admin\controller;

use app\admin\model\Role as AdminRole;
use app\admin\validate\RoleValidate;
use core\ControllerBase;
use core\facades\Flash;
use core\repositories\RoleRepositories;

class Role extends ControllerBase
{
    protected $roleRepositories;

    public function __construct(RoleRepositories $roleRepositories)
    {
        parent::__construct();
        $this->roleRepositories = $roleRepositories;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function show()
    {
        $this->contentHeader('角色','列表');
        $this->message([
            '<span class="label label-sm label-danger">删除操作请谨慎处理！</span>删除角色会将该角色对应的菜单进行删除',
            '<span class="label label-sm label-primary">暂时没有回收站！</span>'
        ]);
        $list = $this->roleRepositories->getAll();

        $this->assign('pageRender',$list->render());

        $this->assign('total',$list->total());

        $this->assign('roleInfo',get_category_tree($list));

        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(RoleValidate $request)
    {
        if (IS_POST)
        {
            $this->store($request);
        }else{
            $this->contentHeader('角色','创建');
            $data = $this->getSelectMenusView($this->getAllowMenusAction());
            $this->assign('menus',$data);
            $this->assign('nodes',get_category_tree($this->roleRepositories->getRoleSelect()));

            return $this->fetch();
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function store(RoleValidate $request)
    {
        if (!$request->check($this->request->except('_token_')))
        {
            Flash::error($request->getError());
            return redirect('admin.role.create');
        }
        $this->roleRepositories->create($this->request->except('__token__'));
        Flash::success('新增成功');
        return redirect('admin.role.create');
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(RoleValidate $request,$id)
    {
        if (IS_POST)
        {
            $this->update($request,$id);
        }else{
            $this->contentHeader('角色','编辑');
            $roleInfo = AdminRole::find($id);
            $data = $this->getSelectMenusView($this->getAllowMenusAction(),$roleInfo['access_id']);
            $this->assign('menus',$data);
            $this->assign('roleInfo',$roleInfo);
            $this->assign('nodes',get_category_tree($this->roleRepositories->getRoleSelect()));
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
    public function update(RoleValidate $request,$id)
    {
        if (!$request->check($this->request->except('_token_')))
        {
            Flash::error($request->getError());
            return redirect('admin.role.edit',['id'=>$id]);
        }
        $this->roleRepositories->update($this->request->except('__token__'),$id);
        Flash::success('更新成功');
        return redirect('admin.role.edit',['id'=>$id]);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $this->roleRepositories->delete($id);
        Flash::success('删除成功');
        return redirect('admin/role/list');
    }
}

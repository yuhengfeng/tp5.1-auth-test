<?php

namespace app\admin\controller;

use app\admin\model\Menus as AdminMenus;
use app\admin\model\RoleMenus;
use app\admin\validate\MenusValidate;
use core\ControllerBase;
use core\facades\Flash;
use core\repositories\MenusRepositories;
use core\repositories\RoleRepositories;
use think\facade\Url;

class Menus extends ControllerBase
{
    protected $menusRepositories;
    protected $roleRepositories;

    public function __construct(MenusRepositories $menusRepositories,RoleRepositories $roleRepositories)
    {
        parent::__construct();
        $this->menusRepositories = $menusRepositories;
        $this->roleRepositories = $roleRepositories;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function show()
    {
        $this->contentHeader('菜单','列表');
        $this->message([
            '<span class="label label-sm label-danger">删除操作请谨慎处理！</span>删除菜单会将所有角色对应的菜单进行删除',
            '<span class="label label-sm label-primary">暂时没有回收站！</span>'
        ]);
        $list = $this->menusRepositories->getAll();

        $this->assign('pageRender',$list->render());

        $this->assign('total',$list->total());

        $this->assign('menusInfo',$list);
//        $this->assign('menusInfo',get_category_tree($list));

        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(MenusValidate $request)
    {
        if (IS_POST)
        {
            $this->store($request);
        }else{
            $this->contentHeader('菜单','创建');
            $this->assign('roles',$this->roleRepositories->getRoleSelect());
            $this->assign('nodes',$this->menusRepositories->getMenusSelect());
            return $this->fetch();
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function store(MenusValidate $request)
    {
        if (!$request->check($this->request->except('_token_')))
        {
            Flash::error($request->getError());
            return redirect('admin.menus.create');
        }
        $result = $this->menusRepositories->create($this->request->except('__token__'));
        if ($result)
        {
            Flash::success('新增成功');
            return redirect('admin.menus.create');
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(MenusValidate $request,$id)
    {
        if (IS_POST)
        {
            $this->update($request,$id);
        }else{
            $this->contentHeader('菜单','编辑');
            $menusInfo = AdminMenus::find($id);
            $this->assign('menusInfo',$menusInfo);
            $this->assign('nodes',$this->menusRepositories->getMenusSelect());
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
    public function update(MenusValidate $request,$id)
    {
        if (!$request->check($this->request->except('_token_')))
        {
            Flash::error($request->getError());
            return redirect('menus/edit',['id'=>$id]);
        }
        $this->menusRepositories->update($this->request->except('__token__'),$id);
        Flash::success('更新成功');
        return redirect('menus/edit',['id'=>$id]);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $this->menusRepositories->delete($id);
        Flash::success('删除成功');
        return redirect('admin/menus/list');
    }
}

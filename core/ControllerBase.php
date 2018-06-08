<?php

namespace core;

use core\facades\Auth;
use core\traits\admin\ViewTraits;
use core\traits\admin\Permission;
use think\Controller;

class ControllerBase extends Controller
{
    use Permission;
    use ViewTraits;

    // 请求参数
    protected $param;

    public function __construct ()
    {
        parent::__construct();

        // 初始化请求信息
        $this->initRequestInfo();

        //初始化响应类型
        $this->initResponseType();

        $this->initLayout();

    }
    /**
     * 初始化请求信息
     */
    final protected function initRequestInfo()
    {
        defined('IS_POST')          or define('IS_POST',         $this->request->isPost());
        defined('IS_GET')           or define('IS_GET',          $this->request->isGet());
        defined('IS_AJAX')          or define('IS_AJAX',         $this->request->isAjax());
        defined('IS_PJAX')          or define('IS_PJAX',         $this->request->isPjax());
        defined('IS_SHOW_MESSAGE')  or define('IS_SHOW_MESSAGE',         config('view.show_message'));
        $this->param = $this->request->except('__token__');
    }
    /**
     * 初始化响应类型
     */
    final private function initResponseType()
    {

        IS_AJAX && !IS_PJAX  ? config('default_ajax_return', 'json') : config('default_ajax_return', 'html');
    }

    /**
     * pjax请求，关闭布局
     */
    final protected function initLayout()
    {
        if (!config('pjax_enable')){
            return;
        }
        $this->request->isPjax() && $this->view->engine->layout(false);
    }


    /**
     * 重写fetch方法
     */
    final protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $this->globalAssign();

        $content = parent::fetch($template, $vars, $replace, $config);

        IS_PJAX && $content = $this->getCrumbsHtml() . $this->contentHeader .(IS_SHOW_MESSAGE ? $this->message : ''). $content;

        return $content;
    }

    /**
     * 全局赋值
     */
    final protected function  globalAssign()
    {
        //菜单栏
        $this->assign('menuView',$this->menuToView($this->getMenusInfo()));
        //面包屑
        !IS_PJAX && $this->assign('crumbsView',$this->getCrumbsHtml());
        //页面头部描述
        $this->assign('contentHeader',$this->contentHeader);
        //列表菜单功能
        $this->assign('actionRender',$this->getTableActionRender());
        //列表中action数据
        $this->assign('action',$this->getAllowMenusAction('menus_list_action'));
        //登陆个人信息
        $this->assign('adminer',Auth::user());
        //页面提示
        IS_SHOW_MESSAGE && $this->assign('message',$this->message);
    }
}

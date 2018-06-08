<?php
namespace core\traits\admin;
use app\admin\model\Menus;

/**
 * Class ViewTraits
 * @package core\traits\admin
 * @author henry
 */
trait ViewTraits
{

    /**
     * @var 标题
     */
    protected $contentHeader;

    /**
     * @var
     * 页面notice
     */
    protected $message;


    /**
     * @param string $title
     * @param string $desc
     * @return $this
     * 设置页面头部
     */
    final protected function contentHeader($title = '',$desc = '')
    {
        $html =  <<<EOT
            <h3 class="page-title">
                $title
            <small>$desc</small>
            </h3>
EOT;
        $this->contentHeader = $title && $desc ? $html : '';
        return $this;
    }

    /**
     * @param string $firsCrumb
     * @param string $secondCrumb
     * @return $this
     * 面包屑
     */
    final protected function getCrumbsHtml()
    {
        $html = <<<EOT
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        {$this->getCrumbs()}
                    </ul>
                </div>
EOT;
        return $html;
    }

    /**
     * @return array
     * 获取面包屑数据
     */
    final function getCrumbs()
    {
        $menusData =  array_reverse(get_crumbs_tree($this->getAllMenusNodes(),$this->getMenusId('id')));
        $str = '';
        foreach ($menusData as $val)
        {
            $str.= '<li><span>'.$val['name'].'</span><i class="fa fa-circle"></i></li>';
        }
        return rtrim($str, '<i class="fa fa-circle"></i>');
    }

    //获取左部功能
    final function getLeftAction()
    {
        $leftActionInfo = $this->getAllowMenusAction('menus_left_action');
        $str = '';
        foreach ($leftActionInfo as $value)
        {
            $str.= '<div class="btn-group"><a id="sample_editable_1_new" href="'.url($value['url']).'" class="btn sbold green"> '.$value['slug'].'<i class="'.$value['icon'].'"></i></a></div>';
        }
        return count($leftActionInfo) ? $str : '';
    }

    //获取特殊功能
    final function getOtherAction()
    {
        $otherAction = $this->getAllowMenusAction('menus_right_action');
        $str = '';
        foreach ($otherAction as $value)
        {
            $str.= '<li><a href="'.url($value['url']).'"><i class="'.$value['icon'].'"></i> '.$value['name'].' </a></li>';
        }
        return count($otherAction) ? $str : '';
    }

    //获取列表左上侧操作视图
    final function getTableActionRender()
    {
        $style = $this->getOtherAction() ? '' : 'hide';
        $html = <<<EOT
<div class="table-toolbar">
                        <div class="row">
                            <div class="col-md-6">
<!--                                左部功能-->
                                {$this->getLeftAction()}

                            </div>
<!--                            工具条-->
                            <div class="col-md-6">
                                <div class="btn-group pull-right">
                                    <button class="btn green  btn-outline dropdown-toggle {$style}" data-toggle="dropdown">
                                        <i class="fa fa-user"></i>
                                        工具条
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-right">
                                        {$this->getOtherAction()}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
EOT;
        return $html;
    }

    //获取列表功能视图
    final function getTableListRender()
    {
        $action = $this->getAllowMenusAction('menus_list_action');
        $str = '';
        foreach ($action as $value)
        {
            $str.= '<div class="btn-group"><a href="'.url($value['url'],['id'=>$value['id']]).'" class="btn btn-outline '.$value['style'].'" > <i class="'.$value['icon'].'"></i> '.$value['slug'].' </a> </a></div>';
        }
        return count($action) ? $str : '';
    }

    /**
     * @param string $title
     * @param array $data
     * @return $this
     * 头部页面提示
     */
    final protected function message($data = [],$title = '注意!')
    {
        $str = '';
        foreach ($data as $v)
        {
            $strSheet = '<p> %s</p>';
            $str.= sprintf($strSheet,$v);
        }
        $html = <<<EOT
                <div class="m-heading-1 border-green m-bordered">
                        <h3>$title</h3>
                        $str
                </div>
EOT;
        $this->message = count($data) ? $html : '';

        return $this;
    }

    /**
     * @return array
     * 获取左部菜单栏数据
     */
    final protected function getMenusInfo()
    {
        return $this->handleMenusNodes($this->getAllMenusNodes());
    }

    /**
     * 获取左部菜单所有的节点数据
     */
    protected function getAllMenusNodes()
    {
        return $this->getAllowMenusAction('menus_nav');
    }

      /**
       * @param array $data
       * @param int $pid
       * @param int $lever
       * @return array
       * 处理无限极菜单节点
       */
    protected function handleMenusNodes($data,$pid = 0)
    {
        $arr = array();

        foreach ($data as $key=>$val)
        {
            if ($val['parent_id'] == $pid){
                $arr[] = $val;
            }
        }
        foreach ($arr as $key=>$value)
        {
            $arr[$key]['child'] = $this->handleMenusNodes($data,$value['id']);
        }
        return $arr;
    }
    /**
     * @param array $data
     * @param string $child
     * @return string
     * 后台左侧菜单
     */
    final protected function menuToView($data = [],$child = 'child')
    {
        $menu_view = '';
        foreach ($data as $v)
        {
            $url = url($v['url']);
            if ($v['header']){
                $header_html = <<<EOT
                <li class="heading">
                    <h3 class="uppercase">{$v['header']}</h3>
                </li>
EOT;
            }else{
                $header_html = '';
            }
            if (!count($v[$child])){
                $icon = empty($v['icon']) ? 'fa-dot-circle-o' : $v['icon'];
                    $menu_view.= <<<EOT
                        $header_html
                <li class="nav-item">
                    <a href="{$url}" class="nav-link">
                        <i class="{$icon}"></i>
                        <span class="title">{$v['name']}</span>
                        <span class="selected"></span>
                    </a>
                </li>
EOT;
            }else{
                $icon = empty($v['icon']) ? 'fa-circle-o' : $v['icon'];
                $menu_view.= <<<EOT
                    $header_html
<li class="nav-item">
                    <a href="{$url}" class="nav-link nav-toggle">
                        <i class="{$icon}"></i>
                        <span class="title">{$v['name']}</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        {$this->menuToView($v[$child])}
                    </ul>
                </li>
EOT;
            }
        }
        return $menu_view;
    }

    /**
     * @param array $data
     * @param string $child
     * @return string
     * 选择权限菜单或节点
     */
    final function getSelectMenusView($data = [],$access_id = '')
    {
        $menu_view = '';
        $accessInfo = array_flip(explode(',',$access_id));
        foreach ($data as $v)
        {
            $result = array_key_exists($v['id'],$accessInfo) ? 'checked' : '';
            $menu_view.= <<<EOT
                    <label class="mt-checkbox mt-checkbox-outline">{$v['name']}
                        <input type="checkbox" value="{$v['id']}" name="menu_id[]" {$result}>
                        <span></span>
                    </label>
EOT;
        }
        return $menu_view;
    }

}

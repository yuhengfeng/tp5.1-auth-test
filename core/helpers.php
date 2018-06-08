<?php
/**
 * Help.php
 * Describe:
 * Created on: 2018/5/22/022 13:47
 * Created by Henry
 */
use \think\Db;
use \think\Container;


if (!function_exists('bcrypt'))
{
    function bcrypt($value,$method = PASSWORD_BCRYPT,$option = [])
    {
        return password_hash($value,$method,$option);
    }
}
if (!function_exists('resource'))
{
    function resource()
    {
        return app('\core\tools\Resource');
    }
}
if (!function_exists('img_full_url'))
{
    function img_full_url($imgPath)
    {
        return (config('resource.img_url')==null) ? request()->domain().$imgPath : config('resource.img_url').'/'.$imgPath;
    }
}

if (!function_exists('getLastSql'))
{
    function getLastSql()
    {
        $sql = Db::table('contract')->getLastSql();
        return $sql;
    }
}

if (!function_exists('dd'))
{
    function dd($array)
    {
        echo '<pre>';
        print_r($array);die;
    }
}

if (!function_exists('public_path'))
{
    function public_path()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }
}
/**
 * 嵌套形无限极分类
 */
if (!function_exists('get_parent_tree'))
{
    function get_parent_tree($data,$pid = 0,$level = 0)
    {
        $arr = array();

        foreach ($data as $key=>$val)
        {
            if ($val['parent_id'] == $pid){
                $val['level'] = $level;
                $arr[] = $val;
            }
        }
        foreach ($arr as $key=>$value)
        {
            $arr[$key]['child'] = get_parent_tree($data,$value['id'],$level+1);
        }
        return $arr;
    }
}
/**
 * 等级 分类
 */
if (!function_exists('get_category_tree'))
{
    function get_category_tree($data,$pid = 0,$level = 0)
    {
        static $arr = [];
        if (is_array($data))
        {
            foreach ($data as $key=>$item)
            {
                if ($item['parent_id'] == $pid){
                    $item['level'] = $level;
                    $arr[] = $item;
                    unset($data[$key]);
                    get_category_tree($data,$item['id'],$level+1);
                }
            }
        }else{
            foreach ($data as $key=>$item)
            {
                if ($item->parent_id == $pid){
                    $item->level = $level;
                    $arr[] = $item;
                    unset($data[$key]);
                    get_category_tree($data,$item->id,$level+1);
                }
            }

        }
        return $arr;
    }
}
if (!function_exists('array_different'))
{
    function array_different($arr1,$arr2)
    {
        return count($arr1)>count($arr2) ? array_diff($arr1,$arr2) : array_diff($arr2,$arr1);
    }
}
if (!function_exists('get_crumbs_tree'))
{
    function get_crumbs_tree($data,$pid)
    {
        static $arr = [];
            foreach ($data as $key=>$item)
            {
                if ($item['id'] == $pid){
                    $arr[] = $item;
                    unset($data[$key]);
                    get_crumbs_tree($data,$item['parent_id']);
                }
            }
        return $arr;
    }
}
if (!function_exists('auth'))
{
    function auth()
    {
        return Container::get('core\contracts\Guard');
    }
}
if (!function_exists('get_current_Uri'))
{
    function get_current_Uri()
    {
        $controller = strtolower(request()->controller());
        $module = strtolower(request()->module());
        $action = strtolower(request()->action());
        return $module.'/'.$controller.'/'.$action;
    }
}
if (!function_exists('create_label_str'))
{
    function create_label_str($name,$type = 'Menus',$style = 'info')
    {
        $data = app('app\admin\model\\'.$type)->all($name)->toArray();
        $label = '';
        foreach ($data as $value){
            $label.= '&nbsp;<span class="label label-sm label-'.$style.'">'.$value['name'].'</span>';
        }
        return $label;
    }
}
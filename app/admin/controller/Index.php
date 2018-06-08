<?php
namespace app\admin\controller;

use core\ControllerBase;

class Index extends ControllerBase
{
    public function index()
    {
        $this->contentHeader('控制台','列表');
        $this->assign('data',$this->getIndexData());
        return $this->fetch('/home');
    }
    /**
     * 获取控制面板数据
     */
    protected function getIndexData()
    {
        $query = new \think\db\Query();

        $system_info_mysql = $query->query("select version() as v;");
        // 系统信息
        $data['auth_version']     = config('console.auth_version');
        $data['think_version']  = config('console.tp_version');
        $data['os']             = PHP_OS;
        $data['software']       = $_SERVER['SERVER_SOFTWARE'];
        $data['mysql_version']  = $system_info_mysql[0]['v'];
        $data['upload_max']     = ini_get('upload_max_filesize');
        $data['php_version']    = PHP_VERSION;

        // 后台信息
        $data['product_name']   = config('console.admin_name');
        $data['author']         = config('console.admin_author');
        $data['website']        = config('console.admin_website');

        return $data;
    }
}

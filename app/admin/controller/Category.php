<?php
namespace app\admin\controller;

use core\ControllerBase;

class Category extends ControllerBase
{
    public function show()
    {
        $this->contentHeader('分类','列表');

        return $this->fetch();
    }
    public function create()
    {
        $this->contentHeader('分类','创建');

        return $this->fetch();
    }
}

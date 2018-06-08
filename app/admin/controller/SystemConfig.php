<?php
namespace app\admin\controller;

use core\ControllerBase;

class SystemConfig extends ControllerBase
{
    public function show()
    {
        $this->contentHeader('控制台','列表');
        return $this->fetch('/home');
    }
}

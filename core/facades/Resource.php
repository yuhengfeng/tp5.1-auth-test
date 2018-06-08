<?php
/**
 * Resource.php
 * Describe:
 * Created on: 2018/5/23/023 11:43
 * Created by Henry
 */
namespace core\facades;

use think\Facade;

class Resource extends Facade
{
    protected static function getFacadeClass()
    {
        return \core\tools\Resource::class;
    }
}
<?php

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class Config extends Model
{
    use SoftDelete;

    protected $table = 'system_config';

    //自动填入创建|修改时间
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';
}

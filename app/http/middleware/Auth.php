<?php

namespace app\http\middleware;

use \core\traits\admin\Permission;
use \core\facades\Auth as Admin;

class Auth
{
    use Permission;

    public function handle($request, \Closure $next)
    {

        if (Admin::check())
        {
            if (!$this->checkPermission($this->getAllowMenusAction())){
                return view('admin@error/403');
            }
            return $next($request);
        }else{
            return redirect('admin/auth/login');
        }
    }

    /**
     * @param $data
     * @return bool
     * 是否有权限访问url
     */
    protected function checkPermission($data)
    {
        $arr = [];
        foreach ($data as $v){
            if ($v['url']){
                $v['url'] = ltrim($v['url'],'/');
                $arr[] = $v;
            }
        }
        $arr = array_column($arr,'url');
        return in_array(request()->path(),$arr) || in_array(get_current_Uri(),$arr);
    }
}

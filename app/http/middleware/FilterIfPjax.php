<?php

namespace app\http\middleware;

use think\Db;

class FilterIfPjax
{
    /**
     * @var View
     */
    protected $view;

    /**
     * FilterIfPjax constructor.
     * @param View $view
     */
    public function __construct(\think\View $view)
    {
        $this->view = $view;
    }

    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        return $response;
    }

    /**
     * @return mixed
     */
    protected function filterLayout()
    {
        return $this->view->engine->layout(false);
    }
}

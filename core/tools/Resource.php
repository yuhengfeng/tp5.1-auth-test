<?php
namespace core\tools;

use core\exception\ErrorException;
use Closure;
use core\tools\layout\Content;

/**
 * Resources.php
 * Describe:
 * Created on: 2018/5/23/
 * Created by Henry
 */

class Resource
{
    /**
     * @var array
     */
    protected static $script = [];
    /**
     * @var array
     */
    protected static $js = [];
    /**
     * @var array
     */
    protected static $css = [];

    /**
     * @var int
     */
    protected static $versionCache;

    /**
     * Resource constructor.
     */
    public function __construct()
    {
        self::$versionCache = 1;
    }

    /**
     * 处理传入的资源路径，添加上版本号
     * @param string $scriptPath
     * @return string
     */
    protected static function resourcePath($scriptPath)
    {
        $isQM = '?';
        if(preg_match('/\?/', $scriptPath)){
            $isQM = '&';
        }
        $scriptPath .= $isQM .'version='. self::$versionCache;
        $scriptPath =  (config('resource.app_url') == null) ? request()->domain().'/'.$scriptPath : config('resource.app_url').'/'.$scriptPath;
        return $scriptPath;
    }
    /**
     * @param array $css
     * @return string
     * 加载css资源
     */
    public static function css(array $css)
    {
        if (!strpos(implode(',',$css),'.css')){
            throw  new ErrorException('css资源格式错误');
        }
        $str = '';
        static::$css = array_merge(static::$css, $css);
        foreach (static::$css as $value)
        {
            $stylesheet = '<link rel="stylesheet" href="%s">';
            $str.= sprintf($stylesheet, self::resourcePath($value)).PHP_EOL;
        }
        return $str;
    }


    /**
     * @param array $js
     * @return string
     * 加载js资源
     */
    public static function js(array $js)
    {
        if (!strpos(implode(',',$js),'.js')){
            throw  new ErrorException('js资源格式错误');
        }
        $str = '';
        static::$js = array_merge(static::$js, $js);
        foreach (static::$js as $value)
        {
            $stylesheet = '<script type="text/javascript" src="%s"></script>';
            $str.= sprintf($stylesheet, self::resourcePath($value)).PHP_EOL;
        }
        return $str;
    }

    /**
     * @param Closure $callback
     * @return Content
     */
    public function content(Closure $callback)
    {
        return new Content($callback);
    }

}
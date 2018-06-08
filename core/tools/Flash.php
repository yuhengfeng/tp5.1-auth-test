<?php

namespace core\tools;
use core\contracts\FlashMessage;
use think\facade\Session;
use think\facade\View;


/**
 * Class Flash
 * @package core\tools
 * @author henry
 */
class Flash
{
    /**
     * @var FlashMessage
     */
    protected $session;
    /**
     * @var
     */
    protected $messages;

    /**
     * Flash constructor.
     * @param FlashMessage $session
     */
    public function __construct(FlashMessage $session)
    {
        $this->session = $session;

        $this->message = [];
    }

    /**
     * @param string $message
     * @return $this
     * 默认提示
     */
    public function info($message = null)
    {
        return $this->message($message,'提示!','alert-info');
    }

    /**
     * @param null $message
     * @return string
     * 警告提示
     */
    public function warning($message = null)
    {
        $res =  $this->message($message,'Warning!','alert-warning');
    }
    /**
     * @param null $message
     * 成功提示
     */
    public function success($message = null)
    {
        return $this->message($message,'Success!','alert-success');
    }
    /**
     * @param null $message
     * 错误提示
     */
    public function error($message = null)
    {
        return $this->message($message,'Error!','alert-danger');
    }

    /***
     * @param null $message
     * @param string $header
     * @param string $type
     * @param bool $close
     * @return string
     */
    protected function message($message = null,$header = 'Info!',$type = 'alert-info',$close = true)
    {
        $close ? $class = 'close' : $class = '';

        $this->messages = [
            'message'   =>  $message,
            'header'    =>  $header,
            'type'      =>  $type,
            'is_close'  =>  $class
        ];

        return $this->flash();
    }

    /**
     * @return $this
     * 闪存数据
     */
    protected function flash()
    {
        $this->session->flash('flash_notification',$this->messages);

        return $this;
    }
}
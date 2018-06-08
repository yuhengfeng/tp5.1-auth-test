<?php
namespace core\src;

use core\contracts\FlashMessage;
use think\Session;

/**
 * Class FlashMessageStore
 * @package core\src
 * @author henry
 */

class FlashMessageStore implements FlashMessage
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function flash($name, $data)
    {
        // TODO: Implement flash() method.
        $this->session->flash($name,$data);
    }
}

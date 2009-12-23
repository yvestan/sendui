<?php
/**
* @package   sendui
* @subpackage common
* @author    Yves Tannier [grafactory.net]
* @copyright 2009 Yves Tannier
* @link      http://www.grafactory.net/sendui
* @license   http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        return $rep;
    }
}


<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * installation
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class installCtrl extends jController {

    // pas d'authentification
    public $pluginParams = array(
        'index' => array('auth.required'=>false),
    );

    // {{{ index()

    /**
     * page d'entrée
     *
     * @template    index
     * @return      html
     */
    public function index() {

        $rep = $this->getResponse('html');

        $rep->title = 'Installation';
        
        $tpl = new jTpl();

        // le template
        $rep->body->assign('MAIN', $tpl->fetch('default_index')); 

        return $rep;

    }
}

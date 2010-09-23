<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Pages d'erreur
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class errorCtrl extends jController {

    // {{{ notfound()

    /**
     * page d'erreur
     *
     * @template    error_notfound
     * @return      html
     */
    public function notfound()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Erreur 404';

        $tpl = new jTpl();

        $rep->body->assign('MAIN', $tpl->fetch('error_notfound')); 

        return $rep;

    }

    // }}}
    
}

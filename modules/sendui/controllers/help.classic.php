<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Pages d'aide
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class helpCtrl extends jController {

    // {{{ index()

    /**
     * page principale
     *
     * @template    help_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Votre compte';

        $tpl = new jTpl();

        $rep->body->assign('MAIN', $tpl->fetch('help_index')); 

        return $rep;

    }

    // }}}

    // {{{ contact()

    /**
     * Formulaire de contact
     * 
     * @template    help_contact
     * @return      html
     */
    public function save()
    {

        $rep = $this->getResponse('html');

        $tpl = new jTpl();

        $rep->body->assign('MAIN', $tpl->fetch('account_credits')); 

        return $rep;

    }

    // }}}

}

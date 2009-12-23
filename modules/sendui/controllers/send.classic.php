<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Envoyer le message
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class controlCtrl extends jController {

    // {{{ index()

    /**
     * page de selection des destinataires parmis les listes
     *
     * @template    settings_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Choix des destinataires';

        $tpl = new jTpl();

        $rep->body->assign('MAIN', $tpl->fetch('recipients_index')); 

        return $rep;
        
    }

    // }}}

    // {{{ save()

    /**
     * Sauvegarder le message et passer à la page suivante
     * 
     * @return      redirect
     */
    public function save()
    {

        $rep = $this->getResponse('html');

        return $rep;

    }

    // }}}

}

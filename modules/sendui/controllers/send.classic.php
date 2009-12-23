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

class sendCtrl extends jController {

    // {{{ index()

    /**
     * Demander la confirmation d'envoi du message
     *
     * @template    send_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Confirmer l\'envoi du message';

        $tpl = new jTpl();

        $rep->body->assign('MAIN', $tpl->fetch('send_index')); 

        return $rep;
        
    }

    // }}}

}

<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Listes des messages
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class messagesCtrl extends jController {

    protected $dao_message = 'common~message';

    protected function _dataTables(&$rep)
    {
        $rep->addJSLink($GLOBALS['gJConfig']->path_app['sendui'].'/js/datatables/js/jquery.dataTables.min.js');
        $rep->addCSSLink($GLOBALS['gJConfig']->path_app['sendui'].'/js/datatables/css/demo_table_jui.css');
    }

    // {{{ drafts()

    /**
     * brouillons
     *
     * @template    messages_drafts
     * @return      html
     */
    public function drafts()
    {

        $rep = $this->getResponse('html');

        $this->_dataTables($rep);

        $rep->title = 'Vos brouillons';

        $tpl = new jTpl();

        // utilisateur
        $session = jAuth::getUserSession();

        // récupérer les brouillons
        $message = jDao::get($this->dao_message);
        $list_drafts = $message->getDrafts($session->idcustomer);
        $tpl->assign('list_drafts', $list_drafts); 

        $rep->body->assign('MAIN', $tpl->fetch('messages_drafts')); 

        return $rep;

    }

    // }}}

    // {{{ sent()

    /**
     * messages déjà envoyés
     * 
     * @template    message_sent
     * @return      html
     */
    public function sent()
    {

        $rep = $this->getResponse('html');

        $this->_dataTables($rep);

        $tpl = new jTpl();

        // utilisateur
        $session = jAuth::getUserSession();

        // récupérer les messages envoyés
        $message = jDao::get($this->dao_message);
        $list_sent = $message->getSent($session->idcustomer);
        $tpl->assign('list_sent', $list_sent); 

        $rep->body->assign('MAIN', $tpl->fetch('messages_sent')); 

        return $rep;

    }

    // }}}

    // {{{ preview()

    /**
     * résumé d'un message avant de l'envoyer
     *
     * @template    messages_preview
     * @return      html
     */
    public function preview()
    {

        // TODO vérifier que le message appartient au client + erreur si pas de idmessage

        $rep = $this->getResponse('html');

        $rep->title = 'Résumé du message';

        $tpl = new jTpl();

        // récupérer le message 
        $message = jDao::get($this->dao_message);
        $message_infos = $message->get($this->param('idmessage'));
        $tpl->assign('message', $message); 

        $rep->body->assign('MAIN', $tpl->fetch('messages_preview')); 

        return $rep;

    }

    // }}}


}

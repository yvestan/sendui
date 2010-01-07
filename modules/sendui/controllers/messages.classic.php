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

    // message et liste
    protected $dao_message = 'common~message';
    protected $dao_message_subscriber_list = 'common~message_subscriber_list';
    protected $dao_subscriber_list = 'common~subscriber_list';
    protected $dao_subscriber_subscriber_list = 'common~subscriber_subscriber_list';

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

    // {{{ draftdelete()

    /**
     * Supprimer un brouillon
     *
     * @return      redirect
     */
    public function draftdelete()
    {

        $rep = $this->getResponse('redirect');

        // TODO tester aussi sur le customer pour la sécurité
        $message = jDao::get($this->dao_message);
        if($message->delete($this->param('idmessage'))) {
            $rep->params = array('delete' => true);
        }
        
        $rep->action = 'sendui~messages:drafts';
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

        // response en ajax
        if($this->param('response')=='ajax') {
            $rep = $this->getResponse('htmlfragment');
            $rep->tplname = 'messages_sent_ajax';
            $rep->tpl->assign('list_sent', $list_sent); 
        } else {
            $rep->body->assign('MAIN', $tpl->fetch('messages_sent')); 
        }

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

        $tpl->assign('idmessage', $this->param('idmessage')); 

        // récupérer le message 
        $message = jDao::get($this->dao_message);
        $message_infos = $message->get($this->param('idmessage'));
        $tpl->assign('message', $message_infos); 

        // les listes associées
        $tpl->assign('message_subscriber_list', $message->getLists($this->param('idmessage'))); 

        $subscriber_subscriber_list = jDao::get($this->dao_subscriber_subscriber_list);
        $tpl->assign('subscriber_subscriber_list', $subscriber_subscriber_list); 

        $rep->body->assign('MAIN', $tpl->fetch('messages_preview')); 

        return $rep;

    }

    // }}}


}

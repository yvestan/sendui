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
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class messagesCtrl extends jController {

    // message et liste
    protected $dao_message = 'common~message';
    protected $dao_message_subscriber_list = 'common~message_subscriber_list';
    protected $dao_subscriber_list = 'common~subscriber_list';
    protected $dao_subscriber = 'common~subscriber';

    // batch
    protected $class_batch = 'sendui~batch';

    protected function _dataTables(&$rep)
    {
        $rep->addJSLink($GLOBALS['gJConfig']->urlengine['basePath'].'js/datatables/js/jquery.dataTables.min.js');
        $rep->addCSSLink($GLOBALS['gJConfig']->urlengine['basePath'].'js/datatables/css/demo_table_jui.css');
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

        // fil d'arianne
        $navigation = array(
            array('action' => '0', 'params' => array(), 'title' => 'Brouillons'),
        );
		$rep->body->assign('navigation', $navigation);

        // menu actif
        $rep->body->assign('active_page', 'drafts');

        $rep->body->assign('MAIN', $tpl->fetch('messages_drafts')); 

        return $rep;

    }

    // }}}

    // {{{ delete()

    /**
     * Supprimer un message
     *
     * @return      redirect
     */
    public function delete()
    {

        $rep = $this->getResponse('redirect');

        // TODO tester aussi sur le customer pour la sécurité
        $message = jDao::get($this->dao_message);
        if($message->delete($this->param('idmessage'))) {
            $rep->params = array('delete' => true);
        }

        // supprimer la table batch
        jClasses::inc($this->class_batch);
        $batch = new Batch($this->param('idmessage'));

        if($batch->isTable()) {
            $batch->deleteTable();   
        }
        
        $rep->action = 'sendui~messages:sent';
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

        $rep->title = 'Messages en cours et envoyés';

        $this->_dataTables($rep);

        $tpl = new jTpl();

        // utilisateur
        $session = jAuth::getUserSession();

        // récupérer les messages envoyés (5) + en cours d'envoi (2) + en attente (1)
        $message = jDao::get($this->dao_message);
        $list_sent = $message->getSent($session->idcustomer);
        $tpl->assign('list_sent', $list_sent); 

        // fil d'arianne
        $navigation = array(
            array('action' => '0', 'params' => array(), 'title' => 'Messages encours &amp; envoyés'),
        );
		$rep->body->assign('navigation', $navigation);

        // menu actif
        $rep->body->assign('active_page', 'archives');

        // supprimer un message ?
        if($this->param('delete')==1) {
            $tpl->assign('delete', $this->param('delete'));
        }

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

        // test ?
        $tpl->assign('success', $this->param('success')); 

        // récupérer le message 
        $message = jDao::get($this->dao_message);
        $message_infos = $message->get($this->param('idmessage'));
        $tpl->assign('message', $message_infos); 

        // nombre de destinataires ety liste
        $subscriber = jDao::get($this->dao_subscriber);
        $tpl->assign('subscriber', $subscriber); 

        jClasses::inc($this->class_batch);
        $batch = new Batch($this->param('idmessage'));

        // pour avoir le bouton "envoyer", le message doit-être HTML et txt, au moins 1 destinataires, le status 0 ou 4
        if(!empty($message_infos->html_message) && !empty($message_infos->text_message) 
            && $message_infos->total_recipients>0 && ($message_infos->status==0 || $message_infos->status==4)) {
            $tpl->assign('ok_to_send', true); 
        }

        // si le message est en cours d'envoi, afficher la progress bar
        if($message_infos->status==2) {

            $progress = jClasses::getService('sendui~progress');
            $progress->view($rep,$this->param('idmessage'),$message_infos->total_recipients);
            $tpl->assign('sending', true);

            // en cours d'envoi
            if(empty($message_infos->sent_end)) {
                $tpl->assign('sending_progress', true);
            }

        }

        // les listes associées
        $tpl->assign('message_subscriber_list', $message->getLists($this->param('idmessage'))); 

        // email de l'utilisateur connecté
        $session = jAuth::getUserSession();
        $tpl->assign('email_customer', $session->email); 

        // la zone étapes
        $tpl->assignZone('steps', 'steps',  array('step' => 4));

        // marquer le menu
        $rep->body->assign('active_page', 'newmessage');

        $rep->body->assign('MAIN', $tpl->fetch('messages_preview')); 

        return $rep;

    }

    // }}}


}

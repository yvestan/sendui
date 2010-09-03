<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Sélection des destinataires
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class recipientsCtrl extends jController {

    // dao des listes et du message
    protected $dao_subscriber_list = 'common~subscriber_list';
    protected $dao_subscriber = 'common~subscriber';
    protected $dao_message_subscriber_list = 'common~message_subscriber_list';
    protected $dao_message = 'common~message';

    // batch
    protected $class_batch = 'sendui~batch';

    /**
     * Pour les tableaux
     */
    protected function _dataTables(&$rep)
    {
        $rep->addJSLink($GLOBALS['gJConfig']->path_app['sendui'].'/js/datatables/js/jquery.dataTables.min.js');
        $rep->addCSSLink($GLOBALS['gJConfig']->path_app['sendui'].'/js/datatables/css/demo_table_jui.css');
    }

    // {{{ index()

    /**
     * page de selection des destinataires parmis les listes
     *
     * @template    recipients_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $this->_dataTables($rep);

        $rep->title = 'Choix des destinataires';

        $tpl = new jTpl();

        $session = jAuth::getUserSession();

        // récupérer les infos sur le message
        $message = jDao::get($this->dao_message);
        $message_infos = $message->get($this->param('idmessage'));
        $tpl->assign('message', $message_infos); 
        $tpl->assign('idmessage', $this->param('idmessage')); 

        // provenance
        $tpl->assign('from_page', $this->param('from_page')); 

        // récupérer les listes du client
        $subscriber_list = jDao::get($this->dao_subscriber_list);
        $list_subscribers_lists = $subscriber_list->getActiveByCustomer($session->idcustomer);
        $tpl->assign('list_subscribers_lists', $list_subscribers_lists); 

        // les abonnes
        $tpl->assign('subscriber', jDao::get($this->dao_subscriber));
        
        // quel liste utilise le message
        $message_subscriber_list = jDao::get($this->dao_message_subscriber_list);
        $tpl->assign('message_subscriber_list', $message_subscriber_list); 

        // la zone étapes
        $tpl->assignZone('steps', 'steps',  array('step' => 3));

        // marquer le menu
        $rep->body->assign('active_page', 'newmessage');

        $rep->body->assign('MAIN', $tpl->fetch('recipients_index')); 

        return $rep;
        
    }

    // }}}

    // {{{ save()

    /**
     * Sauvegarder la/les liste(s) choisie avant de passer à la preview
     * 
     * @return      redirect
     */
    public function save()
    {

        $rep = $this->getResponse('redirect');

        // tableau passé en paramètres
        $idsubscriber_list = $this->param('idsubscriber_list');

        // message <> liste
        $message_subscriber_list = jDao::get($this->dao_message_subscriber_list);

        // supprimer toutes les liaisons avant de réenregistrer
        $message_subscriber_list->deleteByMessage($this->param('idmessage'));

        // vérifier que c'est une liste du client TODO
        /*if($subscriber_list->getList($this->param('idsubscriber_list'),$session->idcustomer) { }*/

        if(!empty($idsubscriber_list)) {

            $record = jDao::createRecord($this->dao_message_subscriber_list);
            $record->idmessage = $this->param('idmessage');

            // la/les listes choisies
            $record->idsubscriber_list = (int)$idsubscriber_list;
            $message_subscriber_list->insert($record);

            // si c'est OK, on crée la table batch
            jClasses::inc($this->class_batch);
            $batch = new Batch($this->param('idmessage'));

            // on delete la table actuelle si existe
            if($batch->isTable()) {
                $batch->deleteTable();
            }

            // on copie
            $batch->copyTable();

            // on précise le nombre de subscriber dans la table message
            $message = jDao::get($this->dao_message);
            $record_message = $message->get($this->param('idmessage'));
            $record_message->total_recipients = $batch->countSubscribers();
            $update_message = $message->update($record_message);
            
        }

        // redirige sur message preview
        $rep->params = array('idmessage' => $this->param('idmessage'));

        // rediriger vers la page suivante
        if($this->param('from_page')!='') {
            $rep->action = $this->param('from_page');
        } else {
            $rep->action = 'sendui~messages:preview';
        }

        return $rep;

    }

    // }}}

}

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
    protected $dao_message = 'common~message';


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

        // récupérer les listes du client
        $subscriber_list = jDao::get($this->dao_subscriber_list);
        $list_subscribers_lists = $subscriber_list->getByCustomer($session->idcustomer);
        $tpl->assign('list_subscribers_lists', $list_subscribers_lists); 
        $tpl->assign('dao_subscriber_list', $subscriber_list); 

        $rep->body->assign('MAIN', $tpl->fetch('recipients_index')); 

        return $rep;
        
    }

    // }}}

    // {{{ save()

    /**
     * Sauvegarder la liste choisie avant de passer à la preview
     * 
     * @return      redirect
     */
    public function save()
    {

        $rep = $this->getResponse('redirect');

        // message
        $message = jDao::get($this->dao_message);
        $record =   $message->get($this->param('idmessage'));

        // vérifier que c'est une liste du client TODO
        /*if($subscriber_list->getList($this->param('idsubscriber_list'),$session->idcustomer) {
            
        }*/

        // la liste choisie
        $record->idsubscriber_list = $this->param('idsubscriber_list');
        $message->update($record);

        // redirige sur message preview
        $rep->action = 'sendui~message:preview';
        $rep->params = array('idmessage' => $this->param('idmessage'));

        return $rep;

    }

    // }}}

}

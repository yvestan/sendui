<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Listes des abonnés
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class subscribersCtrl extends jController {

    // les daos pour abonnés
    protected $dao_subscriber_list = 'common~subscriber_list';
    protected $dao_subscriber_subscriber_list = 'common~subscriber_subscriber_list';
    protected $dao_subscriber = 'common~subscriber';

    // formulaire liste
    protected $form_subscriber_list = 'sendui~subscriber_list';

    // formulaire abonné
    protected $form_subscriber = 'sendui~subscriber';

    protected function _dataTables(&$rep)
    {
        $rep->addJSLink($GLOBALS['gJConfig']->path_app['sendui'].'/js/datatables/js/jquery.dataTables.min.js');
        $rep->addCSSLink($GLOBALS['gJConfig']->path_app['sendui'].'/js/datatables/css/demo_table_jui.css');
    }

    // {{{ index()

    /**
     * les listes d'abonnés
     *
     * @template    subscribers_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $this->_dataTables($rep);

        $rep->title = 'Vos listes d\'abonnés';

        $session = jAuth::getUserSession();

        $tpl = new jTpl();

        // dao de liaison

        // récupérer les listes du client
        $subscriber_list = jDao::get($this->dao_subscriber_list);
        $list_subscribers_lists = $subscriber_list->getByCustomer($session->idcustomer);
        $tpl->assign('list_subscribers_lists', $list_subscribers_lists); 

        $subscriber_subscriber_list = jDao::get($this->dao_subscriber_subscriber_list);
        $tpl->assign('subscriber_subscriber_list', $subscriber_subscriber_list); 

        $rep->body->assign('MAIN', $tpl->fetch('subscribers_index')); 

        return $rep;

    }

    // }}}

    // {{{ preparelistview()

    /**
     * Préparation du formulaire
     * Eventuellement, reprendre les paramètres d'une liste existence
     *
     * @return      redirect
     */
    public function preparelistview()
    {

        $rep = $this->getResponse('redirect');

        // formulaire
        $form_subscriber_list = jForms::create($this->form_subscriber_list, $this->param('idsubscriber_list'));

        // initialiser un message
        if($this->param('idsubscriber_list')!='') {
            $form_subscriber_list->initFromDao($this->dao_subscriber_list);
            $rep->params = array('idsubscriber_list' => $this->param('idsubscriber_list'));
        }

        $rep->params['from_page'] = $this->param('from_page');

        // redirection vers index
        $rep->action = 'sendui~subscribers:listview';

        return $rep;

    }

    // }}}

    // {{{ listview()

    /**
     * créer/editer une liste
     * 
     * @template    subscriber_listview
     * @return      html
     */
    public function listview()
    {

        $rep = $this->getResponse('html');

        $tpl = new jTpl();

        // récuperer l'instance de formulaire
        if($this->param('idsubscriber_list')!='') {
            $form_subscriber_list = jForms::get($this->form_subscriber_list, $this->param('idsubscriber_list'));
        } else {
            $form_subscriber_list = jForms::get($this->form_subscriber_list);
        }

        // le créer si il n'existe pas
        if ($form_subscriber_list === null) {
            $rep = $this->getResponse('redirect');
            $rep->params = array(
                'idsubscriber_list' => $this->param('idsubscriber_list'),
                'from_page' => $this->param('from_page'),
            );
            $rep->action = 'sendui~subscribers:preparelistview';
            return $rep;
        }

        $tpl->assign('form_subscriber_list', $form_subscriber_list);
        $tpl->assign('idsubscriber_list', $this->param('idsubscriber_list'));
        $tpl->assign('from_page', $this->param('from_page'));

        $rep->body->assign('MAIN', $tpl->fetch('subscribers_listview')); 

        return $rep;

    }

    // }}}

    // {{{ listsave()

    /**
     * Sauvegarder les paramètres et aller sur l'ajout de subscribers
     * 
     * @return      redirect
     */
    public function listsave()
    {

        $rep = $this->getResponse('redirect');

        // récupere le form
        if($this->param('idsubscriber_list')!='') {
            $form_subscriber_list = jForms::fill($this->form_subscriber_list, $this->param('idsubscriber_list'));
        } else {
            $form_subscriber_list = jForms::fill($this->form_subscriber_list);
        }
        
        // redirection si erreur
        if (!$form_subscriber_list->check()) {
            $rep->params = array(
                'idsubscriber_list' => $this->param('subscriber_list'),
                'from_page' => $this->param('from_page'),
            );
            $rep->action = 'sendui~subscribers:listview';
            return $rep;
        }

        // enregistrer la nouvelle configuration
        $result = $form_subscriber_list->prepareDaoFromControls($this->dao_subscriber_list);

        // client
        $session = jAuth::getUserSession();
        $result['daorec']->idcustomer = $session->idcustomer;

        if($result['toInsert']) {
            $idsubscriber_list = $result['dao']->insert($result['daorec']);
        } else {
            $update = $result['dao']->update($result['daorec']);
        }

        // si ok, on redirige sur  la page suivante
        if(!empty($idsubscriber_list) || !empty($update)) {

            // dans le cas update
            if(empty($idsubscriber_list)) {
                $idsubscriber_list = $this->param('idsubscriber_list');
            }

            // détruire le formulaire
            jForms::destroy($this->form_subscriber_list);

            $rep->params = array('idsubscriber_list' => $idsubscriber_list);

            // rediriger vers la page suivante
            if($this->param('from_page')!='') {
                $rep->action = $this->param('from_page');
            } else {
                $rep->action = 'sendui~subscribers:listview';
            }

            return $rep;

        }

    }

    // }}}

    // {{{ view()

    /**
     * lister les abonnés d'une liste
     * 
     * @template    subscriber_view
     * @return      html
     */
    public function view()
    {

        $rep = $this->getResponse('html');

        $this->_dataTables($rep);
            
        $tpl = new jTpl();

        // infos sur la liste
        $subscriber_list = jDao::get($this->dao_subscriber_list);
        $tpl->assign('subscriber_list', $subscriber_list->get($this->param('idsubscriber_list')));

        // client
        $session = jAuth::getUserSession();

        // récupérer les abonnés à la liste
        $subscriber = jDao::get($this->dao_subscriber);
        $list_subscribers = $subscriber->getByList($this->param('idsubscriber_list'),$session->idcustomer);

        $tpl->assign('list_subscribers', $list_subscribers);
        $tpl->assign('idsubscriber_list', $this->param('idsubscriber_list'));

        $rep->body->assign('MAIN', $tpl->fetch('subscribers_view')); 

        return $rep;

    }

    // }}}

    // {{{ preparesubscriber()

    /**
     * ajouter/modifier un abonné
     * 
     * @return      redirect
     */
    public function preparesubscriber()
    {

        $rep = $this->getResponse('redirect');
        
        // formulaire
        if($this->param('idsubscriber')!='') {
            $form_subscriber = jForms::create($this->form_subscriber, $this->param('idsubscriber'));
        } else {
            $form_subscriber = jForms::create($this->form_subscriber);
        }

        // initialiser un message
        if($this->param('idsubscriber')!='') {
            $form_subscriber->initFromDao($this->dao_subscriber);
        }

        $rep->params = array(
            'idsubscriber' => $this->param('idsubscriber'),
            'idsubscriber_list' => $this->param('idsubscriber_list'),
            'from_page' => $this->param('from_page')
        );


        // redirection vers subscriber
        $rep->action = 'sendui~subscribers:subscriber';

        return $rep;

    }

    // }}}

    // {{{ subscriber()

    /**
     * ajouter/modifier un abonné => formulaire
     * 
     * @template    subscriber_subscriber
     * @return      html
     */
    public function subscriber()
    {

        $rep = $this->getResponse('html');

        $tpl = new jTpl();

        // récuperer l'instance de formulaire
        if($this->param('idsubscriber')!='') {
            $form_subscriber = jForms::get($this->form_subscriber, $this->param('idsubscriber'));
        } else {
            $form_subscriber = jForms::get($this->form_subscriber);
        }

        // le créer si il n'existe pas
        if ($form_subscriber === null) {
            $rep = $this->getResponse('redirect');
            $rep->params = array(
                'idsubscriber' => $this->param('idsubscriber'),
                'idsubscriber_list' => $this->param('idsubscriber_list'),
                'from_page' => $this->param('from_page')
            );
            $rep->action = 'sendui~subscribers:preparesubscriber';
            return $rep;
        }

        $tpl->assign('form_subscriber', $form_subscriber);
        $tpl->assign('idsubscriber', $this->param('idsubscriber'));
        $tpl->assign('idsubscriber_list', $this->param('idsubscriber_list'));
        $tpl->assign('from_page', $this->param('from_page'));

        $rep->body->assign('MAIN', $tpl->fetch('subscribers_subscriber')); 

        return $rep;

    }

    // }}}

    // {{{ subscribersave()

    /**
     * ajouter/modifier un abonné => sauvegarder les modifications
     * 
     * @template    subscribersave
     * @return      redirect
     */
    public function subscribersave()
    {

        // récupere le form
        if($this->param('idsubscriber')!='') {
            $form_subscriber = jForms::fill($this->form_subscriber, $this->param('idsubscriber'));
        } else {
            $form_subscriber = jForms::fill($this->form_subscriber);
        }

        $rep = $this->getResponse('redirect');

        // redirection si erreur
        if (!$form_subscriber->check()) {
            $rep->params = array(
                'idsubscriber' => $this->param('idsubscriber'),
                'idsubscriber_list' => $this->param('idsubscriber_list'),
                'from_page' => $this->param('from_page')
            );
            $rep->action = 'sendui~subscribers:subscriber';
            return $rep;
        }

        // utilisateur
        $session = jAuth::getUserSession();

        // enregistrer 
        $result = $form_subscriber->prepareDaoFromControls($this->dao_subscriber);

        if($result['toInsert']) {

            // client pour la sécurité
            $result['daorec']->idcustomer = $session->idcustomer;

            $result['dao']->insert($result['daorec']);

            // identifiant du nouvel abonné
            $idsubscriber = $result['daorec']->idsubscriber;

        } else {
            $update = $result['dao']->update($result['daorec']);
        }

        // si ok, on redirige sur  la page suivante
        if(!empty($idsubscriber) || !empty($update)) {

            // dans le cas update
            if(empty($idsubscriber)) {
                $idsubscriber = $this->param('idsubscriber');
            }

            // si on a idsubscriber_list, enregistrer dans la bonne liste
            if($this->param('idsubscriber_list')!='' && empty($update)) {

                $subscriber_subscriber_list = jDao::get($this->dao_subscriber_subscriber_list);
                $record = jDao::createRecord($this->dao_subscriber_subscriber_list);

                // utilisateur et sa liste
                $record->idsubscriber = $idsubscriber;
                $record->idsubscriber_list = $this->param('idsubscriber_list');

                // client pour la sécurité
                $record->idcustomer = $session->idcustomer;

                $subscriber_subscriber_list->insert($record);
            }

            // détruire le formulaire
            jForms::destroy($this->form_subscriber);

            $rep->params = array(
                'idsubscriber' => $idsubscriber,
                'idsubscriber_list' => $this->param('idsubscriber_list'),
            );

            // rediriger vers la page suivante
            if($this->param('from_page')!='') {
                $rep->action = $this->param('from_page');
            } else {
                $rep->action = 'sendui~subscribers:subscriber';
            }

            return $rep;

        }

    }

    // }}}

}

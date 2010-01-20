<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Page d'un abonné
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class subscriberCtrl extends jController {

    // les daos pour abonnés
    protected $dao_subscriber_list = 'common~subscriber_list';
    protected $dao_subscriber_subscriber_list = 'common~subscriber_subscriber_list';
    protected $dao_subscriber = 'common~subscriber';

    // customer
    protected $dao_customer = 'common~customer';

    // formulaire abonné
    protected $form_subscriber = 'sendui~subscriber';

    // {{{ index()

    /**
     * abonné
     *
     * @template    subscribers_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Abonné';

        $session = jAuth::getUserSession();

        $tpl = new jTpl();

        return $rep;

    }

    // }}}

    // {{{ prepare()

    /**
     * ajouter/modifier un abonné
     * 
     * @return      redirect
     */
    public function prepare()
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
        $rep->action = 'sendui~subscriber:edit';

        return $rep;

    }

    // }}}

    // {{{ edit()

    /**
     * ajouter/modifier un abonné => formulaire
     * 
     * @template    subscriber_subscriber
     * @return      html
     */
    public function edit()
    {

        // si pas de liste, retour
        if($this->param('idsubscriber_list')=='') {
            $rep = $this->getResponse('redirect');
            $rep->action = 'sendui~subscribers:index';
            return $rep;
        }

        $rep = $this->getResponse('html');

        $rep->title = 'Ajouter des abonnés';

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

        // infos sur la liste TODO check avec le customer
        if($this->param('idsubscriber_list')!='') {
            $subscriber_list = jDao::get($this->dao_subscriber_list);
            $tpl->assign('subscriber_list', $subscriber_list->get($this->param('idsubscriber_list')));
        }

        // infos sur l'utilisateur
        if($this->param('idsubscriber')!='') {
            $subscriber = jDao::get($this->dao_subscriber);
            $tpl->assign('subscriber', $subscriber->get($this->param('idsubscriber')));
        }

        $rep->body->assign('MAIN', $tpl->fetch('subscriber_edit')); 

        return $rep;

    }

    // }}}

    // {{{ save()

    /**
     * ajouter/modifier un abonné => sauvegarder les modifications
     * 
     * @template    subscribersave
     * @return      redirect
     */
    public function save()
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

    // {{{ subscriberdelete()

    /**
     * Supprimer un utilisateur
     *
     * @return      redirect
     */
    public function delete()
    {

        $rep = $this->getResponse('redirect');

        if($this->param('idsubscriber')!='') {
            
            // TODO tester aussi sur le customer pour la sécurité ET double vérification
            $subscriber = jDao::get($this->dao_subscriber);

            if($subscriber->delete($this->param('idsubscriber'))) {
                $rep->params = array(
                    'delete_subscriber' => true,
                    'idsubscriber_list' => $this->param('idsubscriber_list'),
                );
            }
        
        }

        $rep->action = 'sendui~subscribers:view';

        return $rep;

    }

    // }}}

}

<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Settings d'un message
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class settingsCtrl extends jController {

    protected $form_message_settings = 'sendui~message_settings';
    protected $dao_message = 'common~message';

    // {{{ prepare()

    /**
     * Préparation du formulaire
     * Eventuellement, reprendre les paramètres d'un autre mailing et revenir sur l'action view
     *
     * @return      redirect
     */
    public function prepare()
    {

        $rep = $this->getResponse('redirect');

        // formulaire
        $message_settings = jForms::create($this->form_message_settings, $this->param('idmessage'));

        // initialiser un message
        if($this->param('idmessage')!='') {
            $message_settings->initFromDao($this->dao_message);
            $rep->params = array('idmessage' => $this->param('idmessage'));
        }

        // redirection vers index
        $rep->action = 'sendui~settings:index';

        return $rep;

    }

    // }}}

    // {{{ index()

    /**
     * page de réglage du message
     *
     * @template    settings_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Définition de l\'expéditeur et du sujet';

        $tpl = new jTpl();

        // récuperer l'instance de formulaire
        $message_settings = jForms::get($this->form_message_settings, $this->param('idmessage'));

        // le créer si il n'existe pas
        if ($message_settings === null) {
            $rep = $this->getResponse('redirect');
            $rep->params = array('idmessage' => $this->param('idmessage'));
            $rep->action = 'sendui~settings:prepare';
            return $rep;
        }

        $tpl->assign('message_settings', $message_settings);
        $tpl->assign('idmessage', $this->param('idmessage'));

        $rep->body->assign('MAIN', $tpl->fetch('settings_index')); 

        return $rep;

    }

    // }}}

    // {{{ save()

    /**
     * Sauvegarder les paramètres et passer à la page suivante ou revenir sur index
     * 
     * @return      redirect
     */
    public function save()
    {

        $rep = $this->getResponse('redirect');

        // récupere le form
        if($this->param('idmessage')!='') {
            $message_settings = jForms::fill($this->form_message_settings, $this->param('idmessage'));
        } else {
            $message_settings = jForms::fill($this->form_message_settings);
        }
        
        // redirection si erreur
        if (!$message_settings->check()) {
            $rep->action = 'sendui~settings:index';
            return $rep;
        }

        // enregistrer la nouvelle configuration
        $result = $message_settings->prepareDaoFromControls($this->dao_message);

        // client
        $session = jAuth::getUserSession();
        $result['daorec']->idcustomer = $session->idcustomer;

        if($result['toInsert']) {
            $idmessage = $result['dao']->insert($result['daorec']);
        } else {
            $update = $result['dao']->update($result['daorec']);
        }

        // si ok, on redirige sur  la page suivante
        if(!empty($idmessage) || !empty($update)) {

            // dans le cas update
            if(empty($idmessage)) {
                $idmessage = $this->param('idmessage');
            }

            // détruire le formulaire
            if($this->param('idmessage')!='') {
                jForms::destroy($this->form_message_settings, $this->param('idmessage'));
            } else {
                jForms::destroy($this->form_message_settings);
            }

            // rediriger vers la page suivante
            $rep->params = array('idmessage' => $idmessage);
            $rep->action = 'sendui~compose:index';
            return $rep;

        }

    }

    // }}}

}

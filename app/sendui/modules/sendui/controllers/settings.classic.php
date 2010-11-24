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
            $rep->params = array(
                'idmessage' => $this->param('idmessage'),
                'reuse' => $this->param('reuse'),
            );
        }

        $rep->params['from_page'] = $this->param('from_page');

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
            $rep->params = array(
                'idmessage' => $this->param('idmessage'),
                'from_page' => $this->param('from_page'),
                'reuse' => $this->param('reuse'),
            );
            $rep->action = 'sendui~settings:prepare';
            return $rep;
        }

        // la zone étapes
        $tpl->assignZone('steps', 'steps',  array('step' => 1));

        // marquer le menu
        $rep->body->assign('active_page', 'newmessage');

        $tpl->assign('message_settings', $message_settings);
        $tpl->assign('idmessage', $this->param('idmessage'));
        $tpl->assign('from_page', $this->param('from_page'));
        $tpl->assign('reuse', $this->param('reuse'));

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
            $rep->params = array(
                'idmessage' => $this->param('idmessage'),
                'from_page' => $this->param('from_page'),
                'reuse' => $this->param('reuse'),
            );
            $rep->action = 'sendui~settings:index';
            return $rep;
        }

        // enregistrer la nouvelle configuration
        $result = $message_settings->prepareDaoFromControls($this->dao_message);

        // client
        $session = jAuth::getUserSession();
        $result['daorec']->idcustomer = $session->idcustomer;

        // on insert le nombre de batch et la pause entre chaque bacth autorisé pour cet utilisateur
        if(isset($session->batch_quota) || $session->batch_quota==0) {
            $result['daorec']->batch = $session->batch_quota;
        }
        if(isset($session->pause_quota) || $session->pause_quota==0) {
            $result['daorec']->pause = $session->pause_quota;
        }

        if($result['toInsert'] || $this->param('reuse')!='') {

            // réutilisation
            if($this->param('reuse')!='') {
                $result['daorec']->idmessage = null;

                // mettre le status à 0 et vider les sent_start/sent_end et le count_recipients
                $result['daorec']->idmessage = null;
                $result['daorec']->sent_start = null;
                $result['daorec']->sent_end = null;
                $result['daorec']->count_recipients = 0;
                $result['daorec']->total_recipients = 0;
                $result['daorec']->status = 0;

            }

            $result['dao']->insert($result['daorec']);
            $idmessage = $result['daorec']->idmessage;

        } else {
            $update = $result['dao']->update($result['daorec']);
        }

        // si ok, on redirige sur  la page suivante
        if(!empty($idmessage) || !empty($update)) {

            // dans le cas update
            if(empty($idmessage) && $this->param('reuse')=='') {
                $idmessage = $this->param('idmessage');
            }

            // détruire le formulaire
            if($this->param('idmessage')!='') {
                jForms::destroy($this->form_message_settings, $this->param('idmessage'));
            } else {
                jForms::destroy($this->form_message_settings);
            }

            $rep->params = array('idmessage' => $idmessage);

            // rediriger vers la page suivante
            if($this->param('from_page')!='') {
                $rep->action = $this->param('from_page');
            } else {
                $rep->action = 'sendui~compose:index';
            }

            return $rep;

        }

    }

    // }}}

}

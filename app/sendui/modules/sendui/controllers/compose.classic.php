<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Composition d'un message
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class composeCtrl extends jController {

    // formulaire de composition
    protected $form_message_compose = 'sendui~message_compose';

    // dao message
    protected $dao_message = 'common~message';

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
        $message_compose = jForms::create($this->form_message_compose, $this->param('idmessage'));

        // initialiser un message
        if($this->param('idmessage')!='') {
            $message_compose->initFromDao($this->dao_message);
            $rep->params = array('idmessage' => $this->param('idmessage'));
        }

        $rep->params['from_page'] = $this->param('from_page');

        // redirection vers index
        $rep->action = 'sendui~compose:index';

        return $rep;

    }

    // }}}

    // {{{ index()

    /**
     * page de composition du message
     *
     * @template    compose_index.tpl
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Composition du message';

        $tpl = new jTpl();

        // récuperer l'instance de formulaire
        $message_compose = jForms::get($this->form_message_compose, $this->param('idmessage'));

        // provenance
        $rep->params['from_page'] = $this->param('from_page');

        // le créer si il n'existe pas
        if ($message_compose=== null) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'sendui~compose:prepare';
            $rep->params['idmessage'] = $this->param('idmessage');
            return $rep;
        }

        // la zone étapes
        $tpl->assignZone('steps', 'steps',  array('step' => 2));

        $tpl->assign('message_compose', $message_compose);
        $tpl->assign('idmessage', $this->param('idmessage'));
        $tpl->assign('from_page', $this->param('from_page'));

        // galerie de templates

        // menu actif
        $rep->body->assign('active_page', 'newmessage');

        $rep->body->assign('MAIN', $tpl->fetch('compose_index')); 

        return $rep;
        
    }

    // }}}

    // {{{ saveurl()

    /**
     * Sauvegarder le message depuis une URL
     * 
     * @return      redirect
     */
    public function saveurl()
    {

        $rep = $this->getResponse('redirect');
        $rep->params = array('idmessage' => $this->param('idmessage'));

        // récupere le form
        $message_compose = jForms::fill($this->form_message_compose, $this->param('idmessage'));
        
        if (!$message_compose->check()) {
            $rep->action = 'sendui~compose:index';
            return $rep;
        }
        if($this->param('url_file')!='') {
            $html_message = stripslashes($this->param('html_message'));
            $message_compose->setData('html_message',$html_message);
        }

        // enregistrer le message
        if($message_compose->saveToDao($this->dao_message)) {

            // si ok, on redirige sur la page compose
            jForms::destroy($this->form_message_compose);

            if($this->param('from_page')!='') {
                $rep->action = $this->param('from_page');
            } else {
                $rep->action = 'sendui~compose:index';
            }

            return $rep;
            
        }

        // si rien
        $rep->action = 'sendui~compose:index';
        return $rep;
       
    }

    // }}}

    // {{{ save()

    /**
     * Sauvegarder le message et passer à la page suivante
     * 
     * @return      redirect
     */
    public function save()
    {

        $rep = $this->getResponse('redirect');
        $rep->params = array('idmessage' => $this->param('idmessage'));

        // récupere le form
        $message_compose = jForms::fill($this->form_message_compose, $this->param('idmessage'));
        
        if (!$message_compose->check()) {
            $rep->action = 'sendui~compose:index';
            return $rep;
        }

        // récupérer depuis une URL
        if($this->param('url_file')!='') {
            $utils = jClasses::getService('sendui~utils');
            $html_message = $utils->getExternalContent($this->param('url_file'));
            $message_compose->setData('html_message',$html_message);
        } else {
            // stripslahes sur le message en html
            if($this->param('html_message')!='') {
                $html_message = stripslashes($this->param('html_message'));
                $message_compose->setData('html_message',$html_message);
            }
        }

        // enregistrer le message
        if($message_compose->saveToDao($this->dao_message)) {

            // si ok, on redirige sur  la page suivante
            jForms::destroy($this->form_message_compose);

            if($this->param('from_page')!='') {
                $rep->action = $this->param('from_page');
            } else {
                $rep->action = 'sendui~recipients:index';
            }

            return $rep;
            
        }

        // si rien
        $rep->action = 'sendui~compose:index';
        return $rep;
       
    }

    // }}}

    // {{{ preview()

    /**
     * Prévisualisation du message
     *
     * @template    compose_preview
     * @return      html
     */
    public function preview()
    {

        $rep = $this->getResponse('simple');

        $message = jDao::get($this->dao_message);
        $message_infos = $message->get($this->param('idmessage'));

        $rep->title = $message_infos->subject;

        $tpl = new jTpl();

        // on veut afficher quoi ?
        if($this->param('type_msg')=='html_message') {
            $tpl->assign('preview_message', $message_infos->{$this->param('type_msg')});
        } elseif($this->param('type_msg')=='text_message') {
            $tpl->assign('preview_message', nl2br($message_infos->{$this->param('type_msg')}));
        } else {
            $tpl->assign('preview_message', $message_infos->html_message);
        }

        $rep->body->assign('MAIN', $tpl->fetch('compose_preview')); 

        return $rep;
        
    }

    // }}}

}

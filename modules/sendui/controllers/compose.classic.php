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
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class composeCtrl extends jController {

    protected $form_message_compose = 'sendui~message_compose';
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
     * @template    settings_index
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

        $tpl->assign('message_compose', $message_compose);
        $tpl->assign('idmessage', $this->param('idmessage'));
        $tpl->assign('from_page', $this->param('from_page'));

        // galerie de templates

        $rep->body->assign('MAIN', $tpl->fetch('compose_index')); 

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

        // enregistrer le message
        if($message_compose->saveToDao($this->dao_message)) {

            // si ok, on redirige sur  la page suivante
            jForms::destroy($this->form_message_compose);

            if($this->param('from_page')!='') {
                $rep->action = $this->param('from_page');
            } else {
                $rep->action = 'sendui~messages:preview';
            }

            return $rep;
            
        }

        // si rien
        $rep->action = 'sendui~compose:index';
        return $rep;
       
    }

    // }}}

}

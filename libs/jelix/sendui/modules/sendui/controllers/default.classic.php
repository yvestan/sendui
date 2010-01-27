<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * page d'accueil
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class defaultCtrl extends jController {

    protected $dao_message = 'common~message';
    protected $dao_customer = 'common~customer';

    // {{{ index()

    /**
     * page d'entrée
     *
     * @template    index
     * @return      html
     */
    public function index() {

        $rep = $this->getResponse('html');

        $session = jAuth::getUserSession();

        $rep->title = 'Bienvenue '.$session->login;
        
        $tpl = new jTpl();

        // dao message
        $message = jDao::get('common~message');

        // dernier message envoyé
        $last_message = $message->getLast($session->idcustomer);
        $tpl->assign('last_message', $last_message); 

        // message(s) programmé(s) TODO
        $next_message = $message->getNext($session->idcustomer);

        // message en cours d'envoi
        $nb_current_messages = $message->countCurrents($session->idcustomer);
        $tpl->assign('nb_current_messages', $nb_current_messages); 

        if($nb_current_messages>0) {

            $current_messages = $message->getCurrents($session->idcustomer);
            $tpl->assign('current_messages', $current_messages); 

            $nb_subscribers = 20;
            $tpl->assign('nb_subscribers', $nb_subscribers); 

            $progress = jClasses::getService('sendui~progress');
            $progress->view($rep,10,20);
            $tpl->assign('sending', true);

        }

        // crédits disponibles
        $tpl->assign('credits', $session->credit); 

        // menu actif
        $rep->body->assign('active_page', 'dashboard');
 
        $rep->body->assign('MAIN', $tpl->fetch('index')); 

        return $rep;

    }
}

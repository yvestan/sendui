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

        // message(s) programmé(s)
        $next_message = $message->getNext($session->idcustomer);

        // message en cours d'envoi
        $current_message = $message->getCurrent($session->idcustomer);

        // crédits disponibles
        $tpl->assign('credits', $session->credit); 
 
        $rep->body->assign('MAIN', $tpl->fetch('index')); 

        return $rep;

    }
}

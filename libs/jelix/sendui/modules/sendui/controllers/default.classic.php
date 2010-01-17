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

        // message(s) programmé(s)
        $next_message = $message->getNext($session->idcustomer);

        // message en cours d'envoi
        $nb_current_messages = $message->countCurrents($session->idcustomer);
        $tpl->assign('nb_current_messages', $nb_current_messages); 

        if($nb_current_messages>0) {
            $current_messages = $message->getCurrents($session->idcustomer);
            $tpl->assign('current_messages', $current_messages); 
        }

        // ajout javascript pour progression
        $rep->addJSLink($GLOBALS['gJConfig']->path_app['sendui'].'/js/progressbar/jquery.progressbar.min.js');

        // ajoute les infos
        $js_more = '
            var idmessage = '.$idmessage.';
            var link_status = \''.jUrl::get('sendui~send:process', array('idmessage' => $idmessage)).'\';
            var nb_subscribers = '.$nb_subscribers.';
            var path_app = \''.$GLOBALS['gJConfig']->path_app['sendui'].'\';
        ';
        $rep->addJSCode($js_more);
        $rep->addHeadContent('<script type="text/javascript" src="'.$GLOBALS['gJConfig']->path_app['sendui'].'/js/state.js" ></script>');

        $tpl->assign('nb_subscribers', $nb_subscribers); 

        // crédits disponibles
        $tpl->assign('credits', $session->credit); 
 
        $rep->body->assign('MAIN', $tpl->fetch('index')); 

        return $rep;

    }
}

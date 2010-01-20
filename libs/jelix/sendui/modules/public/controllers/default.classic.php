<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * page d'accueil et action principale (subscribe/unsubscribe)
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

    // les daos pour abonnés
    protected $dao_subscriber_list = 'common~subscriber_list';
    protected $dao_subscriber_subscriber_list = 'common~subscriber_subscriber_list';
    protected $dao_subscriber = 'common~subscriber';

    // log
    protected $log_process = true;
    protected $log_file = 'public';

    // {{{ index()

    /**
     * page d'attente
     *
     * @template    default_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');
        echo jUrl::get('public~default:subscribe');

        $rep->title = 'Accueil';

        $tpl = new jTpl();
        $rep->body->assign('MAIN', $tpl->fetch('default_index')); 

        return $rep;
    }

    // }}}

    // {{{ subscribe()

    /**
     * inscription
     *
     * @template    default_index
     * @return      html
     */
    public function subscribe() 
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Inscription';

        //print_r($_POST);

        $tpl = new jTpl();

        if(empty($_POST['sendui_token'])) {
            $this->setLog('[FATAL] Pas de champ "sendui_token" ');
            return $rep;
        } else {
            $sendui_token = $_POST['sendui_token'];    
        }

        // checker si l'id du formulaire correspond à un customer existant
        $tokens = explode('_', $_POST['sendui_token']);

        if(empty($tokens) || count($tokens)!=3) {
            $this->setLog('[FATAL] Nombre de variable incorrecte dans le champ "sendui_token" ');
            return $rep;
        } else {
            $tokens_string = $tokens[1].'_'.$tokens[2];    
            $token_list = $tokens[1];    
        }

        /*if(!isValidFormID($tokens[1],$tokens[2])) {
            $this->setLog('[FATAL] Id client ou Id liste incorrect ('.$tokens[1].'/'.$tokens[2].') ');
            return $rep;
        /*SELECT * FROM customer c, subscriber_list sl
        WHERE c.idcustomer=sl.idcustomer
        AND c.active=1
        AND sl.status=1*/

        //}
        print_r($_POST);

        // tester si les champs sont corrects (email correct ?)
        if(empty($_POST['email_'.$token_list])) {
            $this->setLog('[FATAL] Pas de champ "sendui_token" ');
            return $rep;
        } else {
            $email = trim($_POST['email_'.$token_list]);
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setLog('[FATAL] Pas de champ "sendui_token" ');
            return $rep;
        }

        // tester si déjà inscrit
        if($this->isSubscriber($email,$idsubscriber_list)) {
            
        } else {
            
        }

        // récupérer de quoi afficher le logo du client avec $idcustomer=$tokens[2]
        //$customer_infos = $this->getLogo();

        
        $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 

        return $rep;
    }

    // }}}

    // {{{ preparesubscribe()

    /**
     * prepare le formulaire d'inscription
     *
     * @return      redirect
     */
    public function add() {

        $rep = $this->getResponse('html');

        $rep->title = 'Inscription';

        // récupérer le token client+liste

        $tpl = new jTpl();

        $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 

        return $rep;
    }

    // }}}

    protected function setLog($msg)
    {

        // à mettre dans les logs
        $msg = ' -> '.$_SERVER['HTTP_REFERER'].' '.$_SERVER['HTTP_USER_AGENT'];

        // logue dans un fichier
        if($this->log_process) {
            jLog::log($msg, $this->log_file);
        }    

    }

}

?>


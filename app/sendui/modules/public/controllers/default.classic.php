<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Page d'accueil et action principale (subscribe/unsubscribe)
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class defaultCtrl extends jController {

    // les daos pour abonnés
    protected $dao_subscriber = 'common~subscriber';

    // log
    protected $log_process = true;
    protected $log_file = 'public';

    // retour de ligne pour les logs
    protected $n = "\n";

    // {{{ index()

    /**
     * Page d'attente
     *
     * @template    default_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Gestion de votre abonnement';

        $tpl = new jTpl();
        $rep->body->assign('MAIN', $tpl->fetch('default_index')); 

        return $rep;

    }

    // }}}

    // {{{ subscribe()

    /**
     * page de résultat d'inscription
     *
     * @template    default_subscribe
     * @return      html
     */
    public function subscribe() 
    {

        // TODO : faire un token pour éviter le spam

        $rep = $this->getResponse('html');

        // titre meta
        $rep->title = 'Inscription à la liste';

        $tpl = new jTpl();

        // on n'a pas le identite de la liste + client dans le champ
        if(empty($_POST['sendui_token'])) {

            $this->setLog('[FATAL] Pas de champ "sendui_token" ');

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('formulaire_incorrect', true);
            $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            return $rep;

        } else {
            $sendui_token = $_POST['sendui_token'];    
        }

        // checker si l'id du formulaire existe
        $tokens = explode('_', $_POST['sendui_token']);

        if(empty($tokens) || count($tokens)!=3) {

            $this->setLog('[FATAL] Nombre de variable incorrecte dans le champ "sendui_token" ');

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('formulaire_incorrect', true);
            $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            return $rep;

        } else {
            $token_list = $tokens[1];    
        }

        //==> on doit avoir au moins le mail

        // tester si les champs sont corrects (email correct ?)
        if(!isset($_POST['email_'.$token_list])) {

            $this->setLog('[FATAL] Pas de champ "email_'.$token_list.'" ');

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('formulaire_incorrect', true);
            $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            return $rep;

        } else {
            $email = trim($_POST['email_'.$token_list]);
        }

        // tester l'email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $this->setLog('[USER_NOTICE] Email incorrect');

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('email_false', true);
            $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            return $rep;

        }

        // la classe d'inscription
        $subscriber = jClasses::getService('sendui~subscriber');

        // parcourir les champs et les mettre dans le tableau TODO : plus de filtrage
        foreach($subscriber->possible_fields as $k=>$v) {
            if(isset($_POST[$v.'_'.$token_list])) {
                $subscriber_infos[$v] = str_replace('_'.$token_list, '', $_POST[$v.'_'.$token_list]);
            }
        }

        // on essaye d'inscrire via la classe subscribe
        if(!$subscriber->subscribe($subscriber_infos,$token_list)) {
            
            $this->setLog('[USER_NOTICE] '.$subscriber->getSubscriberError('debug'));

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('error_message_subscribe', $subscriber->getSubscriberError('user'));
            $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            return $rep;

        }

        // c'est OK
        $tpl->assign('response_subscribe', true);
        $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            
        return $rep;

    }

    // }}}

    // {{{ unsubscribe()

    /**
     * Page de désincription
     *
     * @template    default_unsubscribe
     * @return      html
     */
    public function unsubscribe() 
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Désinscription de la liste';

        $tpl = new jTpl();

        // on n'a pas le token
        if($this->param('t')=='') {

            $this->setLog('[FATAL] Pas de parametre "t" pour le subscriber_token');

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('subscriber_token', true);
            $rep->body->assign('MAIN', $tpl->fetch('default_unsubscribe')); 
            return $rep;

        } else {
            $subscriber_token = $this->param('t');    
        }

        $subscriber = jClasses::getService('sendui~subscriber');
        
        // dao abonnés et list
        $subscriber_dao = jDao::get($this->dao_subscriber);

        // checker si le token est celui d'un abonné
        if($subscriber_dao->isSubscriberToken($subscriber_token)) {

            // on retrouve les infos sur la liste
            $subscriber_infos = $subscriber_dao->getSubscriberByToken($subscriber_token);
            $tpl->assign('subscriber_infos', $subscriber_infos);

            // on désabonne
            if($this->param('us')==1) {
                if($subscriber->unsubscribe($subscriber_token)) {
                    $tpl->assign('response_unsubscribe', $subscriber_infos);
                    $rep->body->assign('MAIN', $tpl->fetch('default_unsubscribe')); 
                    return $rep;
                }
            }

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('subscriber_token', $subscriber_token);
            $rep->body->assign('MAIN', $tpl->fetch('default_unsubscribe')); 
            return $rep;

        } else {
            
            $this->setLog('[FATAL] le subscriber_token n\'existe pas dans la base');

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('token_incorrect', true);
            $rep->body->assign('MAIN', $tpl->fetch('default_unsubscribe')); 
            return $rep;

        }

        return $rep;

    }

    // }}}

    // {{{ setLog()

    /**
     * Les logs du processus inscription/desinscription
     *
     * @return      null
     */
    protected function setLog($msg)
    {

        // à mettre dans les logs
        if(!empty($_SERVER['HTTP_REFERER'])) {
            $msg .= ' -> '.$_SERVER['HTTP_REFERER'].' '.$_SERVER['HTTP_USER_AGENT'];
        }

        // logue dans un fichier
        if($this->log_process) {
            jLog::log($msg, $this->log_file);
        }    

    }

    // }}}

}

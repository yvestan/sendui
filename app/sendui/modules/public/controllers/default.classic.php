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
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class defaultCtrl extends jController {

    // les daos pour abonnés
    protected $dao_subscriber_list = 'common~subscriber_list';
    protected $dao_subscriber_subscriber_list = 'common~subscriber_subscriber_list';
    protected $dao_subscriber = 'common~subscriber';
    protected $dao_customer = 'common~customer';

    // infos client et liste
    protected $subscriber_list_infos = null;
    protected $customer_infos = null;

    // log
    protected $log_process = true;
    protected $log_file = 'public';

    // silencieux ?
    protected $verbose = true;

    // retour de ligne
    protected $n = "\n";

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
     * @template    default_index
     * @return      html
     */
    public function subscribe() 
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Inscription';

        $tpl = new jTpl();

        $error = null;

        // on n'a pas le token
        if(empty($_POST['sendui_token'])) {

            $this->setLog('[FATAL] Pas de champ "sendui_token" ');

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('formulaire_incorrect', true);
            $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            return $rep;

        } else {
            $sendui_token = $_POST['sendui_token'];    
        }

        // checker si l'id du formulaire correspond à un customer existant
        $tokens = explode('_', $_POST['sendui_token']);

        if(empty($tokens) || count($tokens)!=3) {

            $this->setLog('[FATAL] Nombre de variable incorrecte dans le champ "sendui_token" ');

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('formulaire_incorrect', true);
            $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            return $rep;

        } else {
            $tokens_string = $tokens[1].'_'.$tokens[2];    
            $token_list = $tokens[1];    
        }

        // le jeu formulaire (token 1) + client (token 2) doit-être valide
        if(!$this->isValidFormID($tokens[1],$tokens[2])) {

            $this->setLog('[FATAL] Id client ou Id liste incorrect ('.$tokens[1].'/'.$tokens[2].') ');

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('formulaire_incorrect', true);
            $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            return $rep;


        }

        // on doit avoir au moins le mail

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

        // dao abonnés
        $subscriber = jDao::get($this->dao_subscriber);

        // tester si déjà inscrit avec le mail et l'id de la liste
        if($subscriber->isSubscriber($email,$this->subscriber_list_infos->idsubscriber_list)) {

            $this->setLog('[USER_NOTICE] Email déjà enregistré');

            // prévient simplement l'utilisateur et renvoi
            $tpl->assign('email_exist', true);
            $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            return $rep;

        } else {

            // liste des champs possibles TODO
            /*token
            date_insert*/
            $possible_fields = array(
                'email',
                'fullname',
                'firstname',
                'lastname',
                'phone',
                'mobile',
                'address',
                'zip',
                'city',
                'country',
                'html_format',
                'text_format',
                'subscribe_from',
            );

            // tester si ces champs sont dans le formulaire
            foreach($possible_fields as $k=>$v) {
                    
            }

            // enregistrer l'email dans la bonne liste avec éventuellement les autres infos
            $record_subscriber = jDao::createRecord($this->dao_subscriber);

            // la liste
            $record_subscriber->idsubscriber_list = $this->subscriber_list_infos->idsubscriber_list;

            // le client 
            $record_subscriber->idcustomer = $this->customer_infos->idcustomer;

            // le token 
            $token = md5(uniqid(rand(), true));
            $record_subscriber->token = $token;

            $record_subscriber->email = $email;
            $record_subscriber->html_format = 1;
            $record_subscriber->text_format = 1;
            $record_subscriber->subscribe_from = 'externe';
            $record_subscriber->status = 1;

            // enregistre
            $subscriber->insert($record_subscriber);

            if($record_subscriber->idsubscriber!='') {
                $tpl->assign('response_subscribe', true);
            } else {
                $tpl->assign('error_subscribe', true);
            }

            $rep->body->assign('MAIN', $tpl->fetch('default_subscribe')); 
            
        }

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

        $rep->title = 'Désinscription';

        $tpl = new jTpl();

        $error = null;

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

        // dao abonnés et list
        $subscriber = jDao::get($this->dao_subscriber);

        // checker si le token est celui d'un abonné
        if($subscriber->isSubscriberToken($subscriber_token)) {

            // on retrouve les infos sur la liste
            $subscriber_infos = $subscriber->getSubscriberByToken($subscriber_token);
            $tpl->assign('subscriber_infos', $subscriber_infos);

            // on désabonne
            if($this->param('us')==1) {
                if($subscriber->delete($subscriber_infos->idsubscriber)) {
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

    // {{{ isValidFormID()

    /**
     * Test si le jeu token_list+token_customer est valide
     *
     * @param  string $token_subscriber_list le token de la liste
     * @param  string $token_customer le token du client
     * @return  bool
     */
    protected function isValidFormID($token_subscriber_list,$token_customer)
    {

        // dao client
        $customer = jDao::get($this->dao_customer);
        $customer_infos = $customer->getByPublicToken($token_customer);

        // checker si le client existe
        if($customer_infos->idcustomer<1) {
            return false;
        } else {
            // mettre les infos dans un attribut
            $this->customer_infos = $customer_infos;
        }

        // dao list 
        $subscriber_list = jDao::get($this->dao_subscriber_list);
        $subscriber_list_infos = $subscriber_list->getByTokenCustomer($token_subscriber_list,$customer_infos->idcustomer);

        if($subscriber_list_infos->idsubscriber_list>0) {
            // mettre les infos dans un attribut
            $this->subscriber_list_infos = $subscriber_list_infos;
            return true;    
        }

        return false;

    }

    // }}}

}
?>

<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Gestion des abonnements/désabonnements
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class subscriber {

    // les daos pour abonnés
    protected $dao_subscriber_list = 'common~subscriber_list';
    protected $dao_subscriber = 'common~subscriber';

    // champs d'inscription possible
    public $possible_fields = array(
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

    // code d'erreur
    protected $error_code = null;

    // {{{ getSubscriberError()

    /**
     * les erreurs possible
     *
     * @return      array
     */
    public function getSubscriberError($type=null)
    {

        $errors = array(
            1001 => array(
                'debug' => 'La liste n\'existe pas ou l\'idenfiant/token n\'est pas correct',
                'user' => 'La liste n\'existe pas ou le formulaire n\'est pas correct',
            ),
            '1002' => array(
                'debug' => 'L\'email n\'est pas valide',
                'user' => 'L\'email proposé n\'est pas valide',
            ),
            '1003' => array(
                'debug' => 'Cette email est déjà présent dans la liste',
                'user' => 'Vous êtes déjà inscrit à cette liste',
            ),
            '1004' => array(
                'debug' => 'Une erreur d\'insertion dans la base de données s\'est produite',
                'user' => 'Il y a eu un problème pendant votre inscription',
            ),
            '1005' => array(
                'debug' => 'Une erreur de suppression d\'un abonné est survenue',
                'user' => 'Il y a eu un problème et votre désinscription n\'a pas été prise en compte',
            ),
            '1006' => array(
                'debug' => 'Impossible de trouver l\'abonné correspondant au token',
                'user' => 'Vous n\'êtes pas ou plus inscrit à cette liste.',
            ),
        );

        // on retourne quelques chose : array ou string
        if(isset($errors[$this->error_code])) {
            // un type d'erreur ?
            if(!empty($type) && !empty($errors[$this->error_code][$type])) {
                return $errors[$this->error_code][$type];
            } else {
                return $errors[$this->error_code];
            }
        }

        // nada
        if(!$type) {
            return array();
        } else {
            return null;
        }

    }

    // }}}

    // {{{ setErrorCode()

    /**
     * setter pour le code d'erreur
     *
     * @return null
     * @param int $error_code Le code d'erreur
     */
    public function setErrorCode($error_code) { $this->error_code = $error_code; }

    // }}}
    
    // {{{ subscribe()

    /**
     * Inscription d'un nouvel utilisateur à une liste
     *
     * @return      array
     */
    public function subscribe($infos=array(),$list,$customer=null) 
    {

        $email = $infos['email'];

        // on vérifie que l'email est valide
        if(!$this->isEmailValid($email)) {
            $this->setErrorCode(1002);
            return false;
        }

        // liste d'abonné
        $subscriber_list = jDao::get($this->dao_subscriber_list);

        // on vérifie que la liste existe => soit token, soit id
        if(!$subscriber_list->isSubscriberList($list)) {
            $this->setErrorCode(1001);
            return false;
        }

        // abonné
        $subscriber = jDao::get($this->dao_subscriber);

        // les infos sur la liste
        $subscriber_list_infos = $subscriber_list->getByToken($list);

        if(!isset($subscriber_list_infos->idsubscriber_list)) {
            $subscriber_list_infos = $subscriber_list->get($list);
        }

        // on vérifie que l'email existe dans la liste
        if($subscriber->isSubscriber($email,$subscriber_list_infos->idsubscriber_list)) {
            $this->setErrorCode(1003);
            return false;
        }
       
        //==> on enregistre les infos dans la BDD

        // enregistrer l'email dans la bonne liste avec éventuellement les autres infos
        $record_subscriber = jDao::createRecord($this->dao_subscriber);

        // la liste
        $record_subscriber->idsubscriber_list = $subscriber_list_infos->idsubscriber_list;

        // le client 
        $record_subscriber->idcustomer = $subscriber_list_infos->idcustomer;

        // tester si ces champs sont dans le formulaire
        foreach($this->possible_fields as $k=>$v) {
            if(!empty($infos[$v])) {
                $record_subscriber->{$v} = $infos[$v];
            }
        }

        // le token 
        $token = md5(uniqid(rand(), true));
        $record_subscriber->token = $token;

        // email forcément !
        $record_subscriber->email = $email;

        // champs à préciser
        if(empty($infos['html_format'])) {
            $record_subscriber->html_format = 1;
        }
        if(empty($infos['text_format'])) {
            $record_subscriber->text_format = 1;
        }
        if(empty($infos['subscribe_from'])) {
            $record_subscriber->subscribe_from = 'externe';
        }
        if(empty($infos['status'])) {
            $record_subscriber->status = 1;
        }

        // enregistre
        if(!$subscriber->insert($record_subscriber)) {
            $this->setErrorCode(1004);
            return false;
        }

        return true;

    }

    // }}}

    // {{{ unsubscribe()

    /**
     * Déinscription d'un utilisateur
     *
     * @return      html
     */
    public function unsubscribe($subscriber_token) 
    {

        // dao abonnés et list
        $subscriber = jDao::get($this->dao_subscriber);

        // checker si le token est celui d'un abonné
        if($subscriber->isSubscriberToken($subscriber_token)) {

            // on retrouve les infos
            $subscriber_infos = $subscriber->getSubscriberByToken($subscriber_token);

            // on désabonne
            if(!$subscriber->delete($subscriber_infos->idsubscriber)) {
                $this->setErrorCode(1005);
                return false;
            } else {
                return true;
            }

        }  else {
            $this->setErrorCode(1006);
            return false;
        }

        return true;

    }

    // }}}

    // {{{ isEmailValid()

    /**
     * tester si l'email est valide
     *
     * @return  bool
     */
    public function isEmailValid($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }

    // }}}
    
}

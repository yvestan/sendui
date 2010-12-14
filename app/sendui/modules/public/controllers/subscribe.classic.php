<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Gestion des abonnements API RESTful
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class subscribeCtrl extends jController implements jIRestController {

    // les daos pour abonnés
    protected $classe_subscriber = 'sendui~subscriber';

    // {{{ getRESTParams()

    /** récupère les valeurs envoyées
    *
    *
    */
    protected function getRESTParams() 
    {
        // valeurs
        if (isset($_REQUEST['data'])) {
            $params =  json_decode(stripslashes($_REQUEST['data']));
        } else {
            $raw  = '';
            $httpContent = fopen('php://input', 'r');
            while ($kb = fread($httpContent, 1024)) {
                $raw .= $kb;
            }
            $params = json_decode(stripslashes($raw));
        }

        return $params;

    }

    // }}}

    // {{{ getStatusCodeMessage()

    /**
     * Codes d'erreur rest
     *
     * @return string
     * @param int $status Le code d'erreur
     */
    public function getStatusCodeMessage($status)  
    {  
        $codes = Array(  
            100 => 'Continue',  
            101 => 'Switching Protocols',  
            200 => 'OK',  
            201 => 'Created',  
            202 => 'Accepted',  
            203 => 'Non-Authoritative Information',  
            204 => 'No Content',  
            205 => 'Reset Content',  
            206 => 'Partial Content',  
            300 => 'Multiple Choices',  
            301 => 'Moved Permanently',  
            302 => 'Found',  
            303 => 'See Other',  
            304 => 'Not Modified',  
            305 => 'Use Proxy',  
            306 => '(Unused)',  
            307 => 'Temporary Redirect',  
            400 => 'Bad Request',  
            401 => 'Unauthorized',  
            402 => 'Payment Required',  
            403 => 'Forbidden',  
            404 => 'Not Found',  
            405 => 'Method Not Allowed',  
            406 => 'Not Acceptable',  
            407 => 'Proxy Authentication Required',  
            408 => 'Request Timeout',  
            409 => 'Conflict',  
            410 => 'Gone',  
            411 => 'Length Required',  
            412 => 'Precondition Failed',  
            413 => 'Request Entity Too Large',  
            414 => 'Request-URI Too Long',  
            415 => 'Unsupported Media Type',  
            416 => 'Requested Range Not Satisfiable',  
            417 => 'Expectation Failed',  
            500 => 'Internal Server Error',  
            501 => 'Not Implemented',  
            502 => 'Bad Gateway',  
            503 => 'Service Unavailable',  
            504 => 'Gateway Timeout',  
            505 => 'HTTP Version Not Supported'  
        );  

        return (isset($codes[$status])) ? $codes[$status] : '';  

    }  

    // }}}

    // {{{ get()

    /**
     * GET => recupére les infos sur un abonné
     *
     * @return array
     */
    public function get()
    {

        // réponse au format json
        $rep = $this->getResponse('json');

        $rep->data['success'] = 'false'; 
        $rep->data['message'] = $this->getStatusCodeMessage(501);
        $rep->setHttpStatus('501', $this->getStatusCodeMessage(501));

        return $rep;

    }

    // }}}

    // {{{ post()

    /**
     * POST => ajoute un abonnement
     *
     * @return  array
     */
    public function post()
    {

        // réponse au format json
        $rep = $this->getResponse('json');

        // la classe subscribers
        $subscriber = jClasses::getService('sendui~subscriber');

        // récupérer les paramètres
        $params = $this->getRESTParams();

        // convertir en array
        foreach($params as $k=>$v) {
            $infos[$k] = $v;
        }

        // si c'est OK, on répond 200 sinon, on répond
        if($subscriber->subscribe($infos,$infos['list'])) {
            $rep->data['success'] = true;
            $rep->data['message'] = array(
                'debug' => 'Email ajouté à la liste',
                'user' => 'Votre inscription a bien été prise en compte',
            );
            $rep->setHttpStatus('200', $this->getStatusCodeMessage(200));
        } else {
            $rep->data['success'] = false;
            $rep->data['message'] = $subscriber->getSubscriberError();
            $rep->setHttpStatus('400', $this->getStatusCodeMessage(400));
        }

        return $rep;

    }

    // }}}

    // {{{ put()

    /**
     * Ajouter un abonement
     *
     * @return      mixed
     */
    public function put()
    {

        // réponse au format json
        $rep = $this->getResponse('json');

        $rep->data['success'] = 'false'; 
        $rep->data['message'] = $this->getStatusCodeMessage(501);
        $rep->setHttpStatus('501', $this->getStatusCodeMessage(501));

        return $rep;

    }

    // }}}

    // {{{ delete()

    /**
     * Supprimer un abonnement
     *
     * @return      mixed
     */
    public function delete()
    {

        // réponse au format json
        $rep = $this->getResponse('json');

        // la classe subscribers
        $subscriber = jClasses::getService('sendui~subscriber');

        // récupérer les paramètres
        if(!empty($_GET['list']) && !empty($_GET['email'])) {

            $infos = array(
                'list' => $_GET['list'],
                'email' => $_GET['email'],
            );

            // retrouver le token de l'utilisateur
            $subscriber_infos = $subscriber->getSubscriber($infos['email'],$infos['list']);

            if(isset($subscriber_infos->token)) {
                $subscriber_token = $subscriber_infos->token;
            } else {
                $subscriber_token = 'nonexistant';
            }

        } elseif(!empty($_GET['token'])) {
            $subscriber_token = $_GET['token'];
        } else {
            $subscriber_token = 'nonexistant';
        }

        if(!empty($subscriber_token)) {

            // si c'est OK, on répond 200 sinon, on répond
            if($subscriber->unsubscribe($subscriber_token)) {
                $rep->data['success'] = true;
                $rep->data['message'] = array(
                    'debug' => 'Email supprimé de la liste',
                    'user' => 'Votre désinscription a bien été prise en compte',
                );
                $rep->setHttpStatus('200', $this->getStatusCodeMessage(200));
            } else {
                $rep->data['success'] = false;
                $rep->data['message'] = $subscriber->getSubscriberError();
                $rep->setHttpStatus('400', $this->getStatusCodeMessage(400));
            }

        } else {
            $rep->data['success'] = false;
            $rep->data['message'] = array(
                'debug' => 'Mauvais paramètres',
                'user' => 'Les paramètres ne sont pas correct',
            );
            $rep->setHttpStatus('400', $this->getStatusCodeMessage(400));
        }

        return $rep;

    }
    
    // }}}

}

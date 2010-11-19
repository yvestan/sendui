<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Après l'authentification par la classe jAuth
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class authListener extends jEventListener{

    protected $dao_customer = 'common~customer';
 
    public function onAuthLogin($event) {

        // récup le login
        $login = $event->getParam('login');

        // charge le dao customer
        $customer = jDao::get($this->dao_customer);
        $customer_infos = $customer->getByLogin($login);

        // ajouter l'id client dans la session
        if($customer_infos->idcustomer!='') {
            $_SESSION['idcustomer'] = $customer_infos->idcustomer;
        }

    }
}

?>

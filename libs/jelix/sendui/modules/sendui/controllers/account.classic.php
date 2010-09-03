<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Gestion du compte de l'utilisateur
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class accountCtrl extends jController {

    // les daos
    protected $dao_customer = 'common~customer';
    protected $dao_price = 'common~price';

    // formulaires
    protected $form_customer_settings = 'sendui~customer_settings';

    // {{{ prepare()

    /**
     * Préparation du formulaire
     * Reprendre les paramètres du client
     *
     * @return      redirect
     */
    public function prepare()
    {

        // le client
        $session = jAuth::getUserSession();

        $rep = $this->getResponse('redirect');

        // formulaire
        $customer_settings = jForms::create($this->form_customer_settings, $session->idcustomer);

        // initialiser un message
        if($session->idcustomer!='') {
            $customer_settings->initFromDao($this->dao_customer);
        }

        // redirection vers index
        $rep->action = 'sendui~account:index';

        return $rep;

    }

    // }}}

    // {{{ index()

    /**
     * page de réglage du compte
     *
     * @template    account_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Votre compte';

        $tpl = new jTpl();

        // le client
        $session = jAuth::getUserSession();
        $tpl->assign('session', $session);

        // formulaire
        $customer_settings = jForms::create($this->form_customer_settings, $session->idcustomer);
        $customer_settings->initFromDao($this->dao_customer);
        $tpl->assign('customer_settings', $customer_settings);

        // fil d'arianne
        $navigation = array(
            array('action' => '0', 'params' => array(), 'title' => 'Votre compte'),
        );
		$rep->body->assign('navigation', $navigation);

        $rep->body->assign('MAIN', $tpl->fetch('account_index')); 

        return $rep;

    }

    // }}}

    // {{{ save()

    /**
     * Sauvegarder les paramètres du client
     * 
     * @return      redirect
     */
    public function save()
    {

        $rep = $this->getResponse('redirect');

        // le client
        $session = jAuth::getUserSession();

        // récupere le form
        $customer_settings = jForms::fill($this->form_customer_settings, $session->idcustomer);
        
        // redirection si erreur
        if (!$customer_settings->check()) {
            $rep->action = 'sendui~account:index';
            return $rep;
        }

        // enregistrer la nouvelle configuration
        $result = $customer_settings->prepareDaoFromControls($this->dao_customer);

        // si ok, on redirige sur  la page suivante
        if($result['dao']->update($result['daorec'])) {

            // détruire le formulaire
            jForms::destroy($this->form_customer_settings);

            // rediriger vers index
            $rep->action = 'sendui~account:index';
            return $rep;

        }

    }

    // }}}

    // {{{ credits()

    /**
     * TODO: Ajouter des crédits
     * 
     * @template    account_credits
     * @return      html
     */
    public function credits()
    {

        $rep = $this->getResponse('html');

        $session = jAuth::getUserSession();

        // récupérer le nb de crédits actuels du client
        $customer = jDao::get($this->dao_customer);

        // récupérer la grille de tarifs
        /*$price = jDao::get($this->dao_price);
        $prices_tab = $price->getPrice($session->idcustomer);*/

        $tpl = new jTpl();

        $tpl->assign('credits', $session->credit);

        // fil d'arianne
        $navigation = array(
            array('action' => '0', 'params' => array(), 'title' => 'Vos crédits'),
        );
		$rep->body->assign('navigation', $navigation);


        $rep->body->assign('MAIN', $tpl->fetch('account_credits')); 

        return $rep;

    }

    // }}}

}

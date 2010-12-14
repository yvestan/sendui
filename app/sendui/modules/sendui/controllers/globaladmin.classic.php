<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * L'administrateur général
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class globaladminCtrl extends jController {

    // les daos
    protected $dao_customer = 'common~customer';

    // formulaires
    protected $form_customer_user = 'sendui~customer_user';

    // {{{ _dataTables()

    /**
     * Js et css pour les tables
     *
     * @return      mixed
     */
    protected function _dataTables(&$rep)
    {
        $rep->addJSLink($GLOBALS['gJConfig']->urlengine['basePath'].'js/datatables/js/jquery.dataTables.min.js');
        $rep->addCSSLink($GLOBALS['gJConfig']->urlengine['basePath'].'js/datatables/css/demo_table_jui.css');
    }

    // }}}

    // {{{ index()

    /**
     * page d'accueil de l'administrateur
     *
     * @template    account_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Administration';

        $tpl = new jTpl();

        // liste des utilisateurs
        $customer = jDao::get($this->dao_customer);

        // le client
        $session = jAuth::getUserSession();
        $tpl->assign('session', $session);

        // fil d'arianne
        $navigation = array(
            array('action' => '0', 'params' => array(), 'title' => 'Administration'),
        );
		$rep->body->assign('navigation', $navigation);

        $rep->body->assign('MAIN', $tpl->fetch('globaladmin_index')); 

        return $rep;

    }

    // }}}

    // {{{ prepareuser()

    /**
     * Préparation du formulaire pour ajouter/modifier un utilisateur
     * Reprendre les paramètres du client
     *
     * @return      redirect
     */
    public function prepareuser()
    {

        $rep = $this->getResponse('redirect');

        // formulaire
        if($this->param('idcustomer')!='') {
            $form_customer_user = jForms::create($this->form_customer_user, $this->param('idcustomer'));
        } else {
            $form_customer_user = jForms::create($this->form_customer_user);
        }

        // initialiser un message
        if($this->param('idcustomer')!='') {
            $form_customer_user->initFromDao($this->dao_customer);
            $rep->params = array('idcustomer' => $this->param('idcustomer'));
        }

        $rep->params['from_page'] = $this->param('from_page');

        // redirection vers index
        $rep->action = 'sendui~globaladmin:user';

        return $rep;

    }

    // }}}

    // {{{ user()

    /**
     * Ajouter un utilisateur
     *
     * @template    globaladmin_adduser
     * @return      html
     */
    public function user()
    {

        $rep = $this->getResponse('html');

        $tpl = new jTpl();

        // récuperer l'instance de formulaire
        if($this->param('idcustomer')!='') {
            $form_customer_user = jForms::get($this->form_customer_user, $this->param('idcustomer'));
        } else {
            $form_customer_user = jForms::get($this->form_customer_user);
        }

        // le créer si il n'existe pas
        if ($form_customer_user === null) {
            $rep = $this->getResponse('redirect');
            $rep->params = array(
                'idcustomer' => $this->param('idcustomer'),
                'from_page' => $this->param('from_page'),
            );
            $rep->action = 'sendui~globaladmin:prepareuser';
            return $rep;
        }

        if($this->param('idcustomer')!='') {
            $customer = jDao::get($this->dao_customer);    
            $customer_infos = $customer->get($this->param('idcustomer'));
            $tpl->assign('customer', $customer_infos);
        }

        $tpl->assign('form_customer', $form_customer_user);
        $tpl->assign('idcustomer', $this->param('idcustomer'));
        $tpl->assign('from_page', $this->param('from_page'));

        // fil d'arianne
        if($this->param('idcustomer')!='') {
            $navigation = array(
                array('action' => 'sendui~globaladmin:index', 'params' => array(), 'title' => 'Administration'),
                array('action' => '0', 'params' => array(), 'title' => 'Gérer l\'utilisateur'),
            );
        } else {
            $navigation[] = array('action' => '0', 'params' => array(), 'title' => 'Ajouter un utilisateur');
        }
		$rep->body->assign('navigation', $navigation);

        if($this->param('idcustomer')!='') {
            $rep->title = 'Modifier l\'utilisateur';
        } else {
            $rep->title = 'Ajouter un utilisateur';    
        }

        $rep->body->assign('MAIN', $tpl->fetch('globaladmin_user')); 

        return $rep;

    }

    // }}}

    // {{{ saveuser()

    /**
     * Sauvegarder les paramètres du client
     * 
     * @return      redirect
     */
    public function saveuser()
    {

        $rep = $this->getResponse('redirect');

        // récupere le form
        if($this->param('idcustomer')!='') {
            $form_customer_user = jForms::fill($this->form_customer_user, $this->param('idcustomer'));
        } else {
            $form_customer_user = jForms::fill($this->form_customer_user);
        }

        // on doit checker si l'email existe ou si l'utilisateur existe
        
        // redirection si erreur
        if (!$form_customer_user->check()) {
            $rep->action = 'sendui~globaladmin:user';
            $rep->params = array(
                'idcustomer' => $this->param('idcustomer'),
                'from_page' => $this->param('from_page')
            );
            return $rep;
        }

        // utilisateur
        $session = jAuth::getUserSession();

        // enregistrer la nouvelle configuration
        $result = $form_customer_user->prepareDaoFromControls($this->dao_customer);

        // crypter le mot de passe TODO : meilleur sécurité !
        if($form_customer_user->getData('password')!='') {
            $result['daorec']->password = md5($form_customer_user->getData('password'));
        }

        // nouvel utilisateur
        if($result['toInsert']) {

            // ajouter la public_token
            $result['daorec']->public_token = md5(uniqid(rand(), true));

            // insertion
            $result['dao']->insert($result['daorec']);

            // identifiant nouvel admin
            $idcustomer = $result['daorec']->idcustomer;

        } else {
            $update = $result['dao']->update($result['daorec']);
        }

        // si ok, on redirige sur  la page suivante
        if(!empty($idcustomer) || !empty($update)) {

            // dans le cas update
            if(empty($idcustomer)) {
                $idcustomer = $this->param('idcustomer');
            }

            // détruire le formulaire
            if($this->param('idcustomer')!='') {
                jForms::destroy($this->form_customer_user, $this->param('idcustomer'));
            } else {
                jForms::destroy($this->form_customer_user);
            }

            $rep->params = array('idcustomer' => $idcustomer);

            // rediriger vers la page suivante
            if($this->param('from_page')!='') {
                $rep->action = $this->param('from_page');
            } else {
                $rep->action = 'sendui~globaladmin:user';
            }

            return $rep;

        }

    }

    // }}}

    // {{{ userlist()

    /**
     * Liste des utilisateurs
     *
     * @template    userlist
     * @return      html
     */
    public function userlist()
    {
            
        $rep = $this->getResponse('html');

        $this->_dataTables($rep);

        $rep->title = 'Administration - liste des utilisateurs';

        $tpl = new jTpl();

        // le client
        $session = jAuth::getUserSession();
        $tpl->assign('session', $session);

        if($session->is_admin==0) {
            $tpl->assign('forbiden', true); 
        } else {

            // liste des utilisateurs
            $customer = jDao::get($this->dao_customer);
            $customers_list = $customer->findAll();
            $tpl->assign('customers_list', $customers_list); 

        }

        // fil d'arianne
        $navigation = array(
            array('action' => 'sendui~globaladmin:index', 'params' => array(), 'title' => 'Administration'),
            array('action' => '0', 'params' => array(), 'title' => 'Liste des utilisateurs'),
        );
		$rep->body->assign('navigation', $navigation);

        $rep->body->assign('MAIN', $tpl->fetch('globaladmin_userlist')); 

        return $rep;

        
    }

    // }}}

}

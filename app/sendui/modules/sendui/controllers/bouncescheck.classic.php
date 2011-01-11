<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Gestion des bounces
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class bouncescheckCtrl extends jController {

    // les daos bounce config et bounces
    protected $dao_bounce_config = 'common~bounce_config';
    protected $dao_bounce = 'common~bounce';
    protected $dao_subscriber = 'common~subscriber';

    // formulaire bounce config
    protected $form_bounce_config = 'sendui~bounce_config';

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
     * Les configurations de bounces
     *
     * @template    bouncescheck_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $this->_dataTables($rep);

        $rep->title = 'Vos boîtes de rebonds';

        $session = jAuth::getUserSession();

        $tpl = new jTpl();

        // récupérer les configurations
        $bounce_config = jDao::get($this->dao_bounce_config);
        $list_bounce_config = $bounce_config->getByCustomer($session->idcustomer);
        $tpl->assign('list_bounce_config', $list_bounce_config); 

        // fil d'arianne
        $navigation = array(
            array('action' => '0', 'params' => array(), 'title' => 'Listes des boîtes de rebonds'),
        );
		$rep->body->assign('navigation', $navigation);

        // marquer le menu
        $rep->body->assign('active_page', 'subscribers');

        // response en ajax
        if($this->param('response')=='ajax') {
            $rep = $this->getResponse('htmlfragment');
            $rep->tplname = 'bouncescheck_index_ajax';
            $rep->tpl->assign('list_bounce_config', $list_bounce_config); 
        } else {
            $rep->body->assign('MAIN', $tpl->fetch('bouncescheck_index')); 
        }

        return $rep;

    }

    // }}}

    // {{{ bounceslist()

    /**
     * Les configurations de bounces
     *
     * @template    bouncescheck_index
     * @return      html
     */
    public function bounceslist()
    {

        $rep = $this->getResponse('html');

        $this->_dataTables($rep);

        $rep->title = 'Liste des rebonds';

        $session = jAuth::getUserSession();

        $tpl = new jTpl();

        // récupérer les configurations
        $bounce = jDao::get($this->dao_bounce);
        $list_bounce = $bounce->getByCustomer($session->idcustomer);
        $tpl->assign('list_bounce', $list_bounce); 

        // fil d'arianne
        $navigation = array(
            array('action' => '0', 'params' => array(), 'title' => 'Listes des rebonds'),
        );
		$rep->body->assign('navigation', $navigation);

        // marquer le menu
        $rep->body->assign('active_page', 'subscribers');

        // response en ajax
        $rep->body->assign('MAIN', $tpl->fetch('bouncescheck_bounceslist')); 

        return $rep;

    }

    // }}}

    // {{{ bounceslist_list()

    /**
     * lister les bounces avec datables
     * 
     * @return      json
     */
    public function bounceslist_list()
    {

        // reponse format json
        $rep = $this->getResponse('json');

        // on instancie datatables
        $datas = jClasses::getService('sendui~datatables');

        // les colonnes affichées
        $datas->setColumns(array('status','email','sent_date','date_insert'));

        // l'index de recherche
        $datas->setIndex('idbounce');

        // définir le DAO de recherche
        $datas->setDao($this->dao_bounce);

        // boucle sur les résultats
        foreach($datas->getResults() as $bounce) {
            $row = array(
                '<span class="cross">&nbsp;</span>',
                $bounce->rule_cat,
                $bounce->rule_no,
                $bounce->email,
                $bounce->bounce_type,
                $bounce->diag_code,
                $bounce->date_insert,
                '<a href="'.jUrl::get('sendui~bouncescheck:check', array('idbounce' => $bounce->idbounce, 'from_page' => 'sendui~bouncescheck:index')).'" class="table-go">détails</a>',
            );
            $results[] = $row;
        } 

        $rep->data = $datas->getOutputInfos($results);

        return $rep;

    }

    // }}}

    // {{{ syncbounce()

    /**
     * Relancer la comparaison avec les abonnés
     *
     * @template    bouncescheck_syncbounces
     * @return      redirect
     */
    public function syncbounce()
    {

        $rep = $this->getResponse('redirect');

        $session = jAuth::getUserSession();

        $tpl = new jTpl();

        // TODO : faire ça en une requête

        // récupérer les configurations
        $bounce = jDao::get($this->dao_bounce);
        $list_bounce = $bounce->getByCustomer($session->idcustomer);

        // abonné
        $subscriber = jDao::get($this->dao_subscriber);

        /* si le bounce est présent dans la table subscriber avec le jeu idcustomer/email
            alors on marque le status de l'abonné à 3 */
        $nb_syncbounce = 0;
        foreach($list_bounce as $bounce) {
            if($subscriber->isSubscriberEmail($bounce->email,$session->idcustomer)>0) {
                $subscriber->changeStatus($bounce->email,2,$session->idcustomer);
                $nb_syncbounce++;
            }
        }

        // retour sur la liste des bounces
        $rep->action = 'sendui~bouncescheck:bounceslist';

        // nombre de bounce synchronisé
        $this->params = array('nb_syncbounce' => $nb_syncbounce);

        return $rep;

    }

    // }}}

    // {{{ check()

    /**
     * Traiter les bounces et renvoyer sur bounceslist
     *
     * @return      redirect
     */
    public function check()
    {

        // si on veut voir le process
        if($this->param('view_process')) {
            $rep = $this->getResponse('html');
            $rep->title = 'Traitement des rebonds (bounces)';
        } else {
            $rep = $this->getResponse('redirect');
            $rep->action = 'sendui~bouncescheck:bouncelist';
        }

        $tpl = new jTpl();

        // on récupère l'id et les paramètres de la boite
        if($this->param('idbounce_config')!='') {

            $bounce_config = jDao::get($this->dao_bounce_config);
            $infos_bounce_config = $bounce_config->get($this->param('idbounce_config'));
            $tpl->assign('bounce_config', $infos_bounce_config); 

            // fonction pour logguer tous les bounces
            function logAction($params) {

                $GLOBALS['bounces'][] = $params;

                $session = jAuth::getUserSession();
                $cnx = jDb::getConnection();

                if(!empty($params['email'])) {

                    foreach($params as $k=>$v) {
                        $expr[] = '`'.$k.'`='.$cnx->quote($v);
                    }

                    // idcustomer et idbounce config
                    $expr[] = '`idcustomer`='.$session->idcustomer;
                    $expr[] = '`idbounce_config`='.(int)$_GET['idbounce_config'];

                    $sql = 'INSERT INTO bounce SET '.join(',', $expr).' ON DUPLICATE KEY UPDATE date_insert=NOW(),'.join(',', $expr);
                    $cnx->exec($sql);

                }
            }


            // class de gestion des bounces
            define('_PATH_BMH', JELIX_APP_PATH.'/lib/bounces/');
            include_once _PATH_BMH.'bmh.php';

            // objet 
            $bmh = new BounceMailHandler();

            // config de base
            $bmh->testmode = false; // ne supprime pas de message
            $bmh->purge_unprocessed = false; // control the failed BODY rules output
            $bmh->purge_processed  = false; // supprimer également les mails non traité comme retour

            // boite a analyser
            $bmh->mailhost = $infos_bounce_config->mail_host;
            $bmh->mailbox_username = $infos_bounce_config->mail_username;
            $bmh->mailbox_password = $infos_bounce_config->mail_password;
            $bmh->port = $infos_bounce_config->mail_port;
            $bmh->service = $infos_bounce_config->mail_service;
            $bmh->service_option = $infos_bounce_config->mail_service_option;

            // silencieux
            $bmh->verbose = VERBOSE_QUIET;

            // callback
            $bmh->log_function='logAction';
            $bmh->action_function='bounceAction';
            $bmh->unmatched_function='unmatchedAction';

            // ouvrir la connexion avec la boite mail
            $bmh->openPop3(
                $infos_bounce_config->mail_host,
                $infos_bounce_config->mail_username,
                $infos_bounce_config->mail_password,
                $infos_bounce_config->mail_port,
                $infos_bounce_config->mail_service,
                $infos_bounce_config->mail_service_option
            );

            // lancer la n'alyse
            $bmh->processMailbox(6000);

            $tpl->assign('bounces', $GLOBALS['bounces']);

        } else {
            $tpl->assign('invalid_config', true);    
        }
        
        // si on veut voir le process
        if($this->param('view_process')) {

            // fil d'arianne
            $navigation = array(
                array('action' => '0', 'params' => array(), 'title' => 'Gestion des rebonds'),
            );
            $rep->body->assign('navigation', $navigation);

            $rep->body->assign('MAIN', $tpl->fetch('bouncescheck_check')); 

        }

        return $rep;

    }

    // }}}

    // {{{ prepare()

    /**
     * Préparation du formulaire
     * Eventuellement, reprendre les paramètres d'une config existante
     *
     * @return      redirect
     */
    public function prepare()
    {

        $rep = $this->getResponse('redirect');

        // formulaire
        if($this->param('idbounce_config')!='') {
            $form_bounce_config = jForms::create($this->form_bounce_config, $this->param('idbounce_config'));
            $form_bounce_config->initFromDao($this->dao_bounce_config);
            $rep->params = array('idbounce_config' => $this->param('idbounce_config'));
        } else {
            $form_bounce_config = jForms::create($this->form_bounce_config);
        }

        $rep->params['from_page'] = $this->param('from_page');

        // redirection vers index
        $rep->action = 'sendui~bouncescheck:edit';

        return $rep;

    }

    // }}}

    // {{{ edit()

    /**
     * créer/editer une config de bounce
     * 
     * @template    bouncescheck_edit
     * @return      html
     */
    public function edit()
    {

        $rep = $this->getResponse('html');

        $tpl = new jTpl();

        // récuperer l'instance de formulaire
        if($this->param('idbounce_config')!='') {
            $form_bounce_config = jForms::get($this->form_bounce_config, $this->param('idbounce_config'));
        } else {
            $form_bounce_config = jForms::get($this->form_bounce_config);
        }

        // le créer si il n'existe pas
        if ($form_bounce_config === null) {
            $rep = $this->getResponse('redirect');
            $rep->params = array(
                'idbounce_config' => $this->param('idbounce_config'),
                'from_page' => $this->param('from_page'),
            );
            $rep->action = 'sendui~bouncescheck:prepare';
            return $rep;
        }

        if($this->param('idbounce_config')!='') {
            $bounce_config = jDao::get($this->dao_bounce_config);    
            $bounce_config_infos = $bounce_config->get($this->param('idbounce_config'));
            $tpl->assign('bounce_config', $bounce_config_infos);
        }

        $tpl->assign('form_bounce_config', $form_bounce_config);
        $tpl->assign('idbounce_config', $this->param('idbounce_config'));
        $tpl->assign('from_page', $this->param('from_page'));

        // fil d'arianne
        if($this->param('idbounce_config')!='') {
            $navigation = array(
                array('action' => 'sendui~bouncescheck:index', 'params' => array(), 'title' => $bounce_config_infos->name),
                array('action' => '0', 'params' => array(), 'title' => 'Gérer une configuration'),
            );
        } else {
            $navigation[] = array('action' => '0', 'params' => array(), 'title' => 'Créer une configuration');
        }
		$rep->body->assign('navigation', $navigation);

        // marquer le menu
        $rep->body->assign('active_page', 'subscribers');

        if($this->param('idbounce_config')!='') {
            $rep->title = 'Gérer une configuration';
        } else {
            $rep->title = 'Créer une configuration';    
        }

        $rep->body->assign('MAIN', $tpl->fetch('bouncescheck_edit')); 

        return $rep;

    }

    // }}}

    // {{{ save()

    /**
     * Sauvegarder les paramètres et retourner sur l'index
     * 
     * @return      redirect
     */
    public function save()
    {

        $rep = $this->getResponse('redirect');

        // récupere le form
        if($this->param('idbounce_config')!='') {
            $form_bounce_config = jForms::fill($this->form_bounce_config, $this->param('idbounce_config'));
        } else {
            $form_bounce_config = jForms::fill($this->form_bounce_config);
        }
        
        // redirection si erreur
        if (!$form_bounce_config->check()) {
            $rep->params = array(
                'idbounce_config' => $this->param('bounce_config'),
                'from_page' => $this->param('from_page'),
            );
            $rep->action = 'sendui~bouncescheck:index';
            return $rep;
        }

        // enregistrer la nouvelle configuration
        $result = $form_bounce_config->prepareDaoFromControls($this->dao_bounce_config);

        // client
        $session = jAuth::getUserSession();
        $result['daorec']->idcustomer = $session->idcustomer;

        if($result['toInsert']) {
            $token = uniqid();
            $result['daorec']->token = $token;
            $idbounce_config = $result['dao']->insert($result['daorec']);
        } else {
            $update = $result['dao']->update($result['daorec']);
        }

        // si ok, on redirige sur  la page suivante
        if(!empty($idbounce_config) || !empty($update)) {

            // dans le cas update
            if(empty($idbounce_config)) {
                $idbounce_config = $this->param('idbounce_config');
            }

            // détruire le formulaire
            jForms::destroy($this->form_bounce_config);

            $rep->params = array('idbounce_config' => $idbounce_config);

            // rediriger vers la page suivante
            if($this->param('from_page')!='') {
                $rep->action = $this->param('from_page');
            } else {
                $rep->action = 'sendui~bouncescheck:index';
            }

            return $rep;

        }

    }

    // }}}

}

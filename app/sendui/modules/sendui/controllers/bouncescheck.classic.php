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

    // les daos
    protected $dao_bounce_config = 'common~bounce_config';

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

        $rep->title = 'Vos boites de retour';

        $session = jAuth::getUserSession();

        $tpl = new jTpl();

        // récupérer les configurations
        $bounce_config = jDao::get($this->dao_bounce_config);
        $list_bounce_config = $bounce_config->getByCustomer($session->idcustomer);
        $tpl->assign('list_bounce_config', $list_bounce_config); 

        // fil d'arianne
        $navigation = array(
            array('action' => '0', 'params' => array(), 'title' => 'Listes des boîtes de retour'),
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

    public function check()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Traitement des adresses invalides et des retours (bounces)';

        $tpl = new jTpl();

        // on récupère l'id et les paramètres de la boite
        if($this->param('idbounce_config')!='') {

            $bounce_config = jDao::get($this->dao_bounce_config);
            $infos_bounce_config = $bounce_config->get($this->param('idbounce_config'));
            $tpl->assign('bounce_config', $infos_bounce_config); 

            // Use ONE of the following -- all echo back to the screen
            //require_once('callback samples/callback_echo.php');
            //require_once('callback samples/callback_database.php'); // NOTE: Requires modification to insert your database settings
            //require_once('callback samples/callback_csv.php'); // NOTE: Requires creation of a 'logs' directory and making writable

            // class de gestion des bounces
            define('_PATH_BMH', JELIX_APP_PATH.'/lib/bmh/');
            include_once _PATH_BMH.'class.phpmailer-bmh.php';

            // testing examples
            $bmh = new BounceMailHandler();
            //$bmh->action_function    = 'callbackAction'; // default is 'callbackAction'
            //$bmh->verbose            = VERBOSE_SIMPLE; //VERBOSE_REPORT; //VERBOSE_DEBUG; //VERBOSE_QUIET; // default is VERBOSE_SIMPLE
            //$bmh->use_fetchstructure = true; // true is default, no need to speficy
            //$bmh->testmode           = false; // false is default, no need to specify
            //$bmh->debug_body_rule    = false; // false is default, no need to specify
            //$bmh->debug_dsn_rule     = false; // false is default, no need to specify
            //$bmh->purge_unprocessed  = false; // false is default, no need to specify
            //$bmh->disable_delete     = false; // false is default, no need to specify

            /*
             * for remote mailbox
             */
            $bmh->mailhost = $infos_bounce_config->mail_host;
            $bmh->mailbox_username = $infos_bounce_config->mail_username;
            $bmh->mailbox_password = $infos_bounce_config->mail_password;
            $bmh->port = $infos_bounce_config->mail_port;
            $bmh->service = $infos_bounce_config->mail_service;
            $bmh->service_option = $infos_bounce_config->mail_service_option;

            // autres options
            //$bmh->boxname            = 'INBOX'; // the mailbox to access, default is 'INBOX'
            //$bmh->moveHard           = true; // default is false
            //$bmh->hardMailbox        = 'INBOX.hardtest'; // default is 'INBOX.hard' - NOTE: must start with 'INBOX.'
            //$bmh->moveSoft           = true; // default is false
            //$bmh->softMailbox        = 'INBOX.softtest'; // default is 'INBOX.soft' - NOTE: must start with 'INBOX.'
            //$bmh->deleteMsgDate      = '2009-01-05'; // format must be as 'yyyy-mm-dd'

            /*
             * rest used regardless what type of connection it is
             */
            $bmh->openMailbox();
            $bmh->processMailbox();

            $tpl->assign('bounces', $GLOBALS['bounces']);

        } else {
            $tpl->assign('invalid_config', true);    
            
        }
        
        // fil d'arianne
        $navigation = array(
            array('action' => '0', 'params' => array(), 'title' => 'Gestion des retours'),
        );
		$rep->body->assign('navigation', $navigation);

        $rep->body->assign('MAIN', $tpl->fetch('bouncescheck_check')); 

        return $rep;

    }

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

    
/* This is a sample callback function for PHPMailer-BMH (Bounce Mail Handler).
 * This callback function will echo the results of the BMH processing.
 */

/* Callback (action) function
 * @param int     $msgnum        the message number returned by Bounce Mail Handler
 * @param string  $bounce_type   the bounce type: 'antispam','autoreply','concurrent','content_reject','command_reject','internal_error','defer','delayed'        => array('remove'=>0,'bounce_type'=>'temporary'),'dns_loop','dns_unknown','full','inactive','latin_only','other','oversize','outofoffice','unknown','unrecognized','user_reject','warning'
 * @param string  $email         the target email address
 * @param string  $subject       the subject, ignore now
 * @param string  $xheader       the XBounceHeader from the mail
 * @param boolean $remove        remove status, 1 means removed, 0 means not removed
 * @param string  $rule_no       Bounce Mail Handler detect rule no.
 * @param string  $rule_cat      Bounce Mail Handler detect rule category.
 * @param int     $totalFetched  total number of messages in the mailbox
 * @return boolean
 */
function callbackAction ($msgnum, $bounce_type, $email, $subject, $xheader, $remove, $rule_no=false, $rule_cat=false, $totalFetched=0) {
    $GLOBALS['bounces'][] = array(
        'msgnum' => $msgnum,
        'bounce_type' => $bounce_type,
        'email' => $email,
        'subject' => $subject,
        'xheader' => $xheader,
        'remove' => $remove,
        'rule_no' => $rule_no,
        'rule_cat' => $rule_cat,
    );
    return true;
}


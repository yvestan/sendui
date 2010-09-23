<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * installation
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class installCtrl extends jController {

    // pas d'authentification
    public $pluginParams = array(
        '*' => array('auth.required' => false),
    );
    
    // {{{ prepare_index()

    /**
     * Préparation du formulaire d'installation base de données
     *
     * @return      redirect
     */
    public function prepare_index() { return $this->prepare_form('index'); }

    // }}}

    // {{{ index()

    /**
     * page d'entrée formulaire base de données
     *
     * @template    index
     * @return      html
     */
    public function index() { return $this->get_form('index','Accès à la base de données'); }

    // }}}

    // {{{ save_index()

    /**
      sauvegarde paramètre bdd
     *
     * @template    index
     * @return      html
     */
    public function save_index() { return $this->save_form('index','smtpmail'); }

    // }}}

    // {{{ prepare_smtpmail()

    /**
     * Préparation du formulaire d'installation smtp
     *
     * @return      redirect
     */
    public function prepare_smtpmail() { return $this->prepare_form('smtpmail'); }

    // }}}

    // {{{ smtpmail()

    /**
     * page d'entrée formulaire base de données
     *
     * @template    smtpmail
     * @return      html
     */
    public function smtpmail() { return $this->get_form('smtpmail','Configuration du serveur d\'envoi'); }

    // }}}

    // {{{ save_smtpmail()

    /**
     * sauvegarde paramètre bdd
     *
     * @template    index
     * @return      html
     */
    public function save_smtpmail() { return $this->save_form('smtpmail','user'); }

    // }}}

    // {{{ prepare_user()

    /**
     * Préparation du formulaire de créa d'un utilisateur
     *
     * @return      redirect
     */
    public function prepare_user() { return $this->prepare_form('user'); }

    // }}}

    // {{{ user()

    /**
     * page d'entrée formulaire utilisateur
     *
     * @template    user
     * @return      html
     */
    public function user() { return $this->get_form('user','Création de l\'utilisateur'); }

    // }}}

    // {{{ save_user()

    /**
     * sauvegarde utilisateur dans la table customer
     *
     * @return      redirect
     */
    public function save_user() { return $this->save_form('user','end'); }

    // }}}

    // {{{ save_form()

    /**
     * Sauvegarder les paramètres et passer à la page suivante ou revenir sur index
     * 
     * @return      redirect
     */
    public function save_form($action,$next)
    {

        $rep = $this->getResponse('redirect');

        // on récup le form
        $form_infos = jForms::fill('sendui~install_'.$action);

        // invalide : on redirige vers l'action d'affichage
        if (!$form_infos->check()) {
            $rep->action = 'sendui~install:'.$action;
            return $rep;
        }
               
        $file_init = array(
            'smtpmail' => array(
                'path' => 'cmdline/',
                'file' => 'cli',
                'zone' => 'mailerInfo',
                'values' => array('sSmtpUsername','sSmtpPassword','sSmtpHost'),
            ),
            'index' => array(
                'path' => '',
                'file' => 'dbprofils',
                'zone' => 'sendui',
                'values' => array('driver','database','host','user','password'),
            ),
        );

        // on récup le fichier d'exemple
        $ini =  new jIniFileModifier(JELIX_APP_CONFIG_PATH.$file_init[$action]['path'].$file_init[$action]['file'].'-example.ini.php');

        foreach($file_init[$action]['values'] as $v) {
            $init_infos[$v] = $ini->getValue($v, $file_init[$action]['zone']);
            $ini->setValue($v, $form_infos->getData($v),$file_init[$action]['zone']);
        }

        $ini->saveAs(JELIX_APP_CONFIG_PATH.$file_init[$action]['path'].$file_init[$action]['file'].'.ini.php');

        // rediriger vers la page suivante
        $rep->action = 'sendui~install:'.$next;

        return $rep;

    }

    // }}}

    // {{{ get_form()

    /**
     * creation du formulaire
     *
     * @template    index
     * @return      html
     */
    public function get_form($action,$title)
    {

        $rep = $this->getResponse('install');

        $rep->title = 'Installation - '.$title;
        
        $tpl = new jTpl();

        // récuperer l'instance de formulaire
        $install = jForms::get('sendui~install_'.$action);

        // le créer si il n'existe pas
        if ($install === null) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'sendui~install:prepare_'.$action;
            return $rep;
        }

        $tpl->assign('install_'.$action, $install);

        // le template
        $rep->body->assign('MAIN', $tpl->fetch('install_'.$action)); 

        return $rep;

    }

    // }}}

    // {{{ prepare_form()

    /**
     * Préparation du formulaire d'installation
     *
     * @return      redirect
     */
    public function prepare_form($action)
    {

        $rep = $this->getResponse('redirect');

        // formulaire
        $install = jForms::create('sendui~install_'.$action);

        // redirection vers index
        $rep->action = 'sendui~install:'.$action;

        return $rep;

    }

    // }}}

}

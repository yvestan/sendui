<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Listes des abonnés
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */


// dossier d'upload
define('UPLOAD_DIR_PATH', JELIX_APP_WWW_PATH.'admin/upload/');

class subscribersCtrl extends jController {

    // les daos pour abonnés
    protected $dao_subscriber_list = 'common~subscriber_list';
    protected $dao_subscriber = 'common~subscriber';

    // customer
    protected $dao_customer = 'common~customer';

    // formulaire liste
    protected $form_subscriber_list = 'sendui~subscriber_list';

    // formulaire upload
    protected $form_subscribers_file = 'sendui~subscribers_file';

    // formulaire plusieurs subscribers
    protected $form_subscribers_text = 'sendui~subscribers_text';

    // formulaire abonné
    protected $form_subscriber = 'sendui~subscriber';

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
     * les listes d'abonnés
     *
     * @template    subscribers_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $this->_dataTables($rep);

        $rep->title = 'Vos listes d\'abonnés';

        $session = jAuth::getUserSession();

        $tpl = new jTpl();

        // dao de liaison

        // récupérer les listes du client
        $subscriber_list = jDao::get($this->dao_subscriber_list);
        $list_subscribers_lists = $subscriber_list->getByCustomer($session->idcustomer);
        $tpl->assign('list_subscribers_lists', $list_subscribers_lists); 

        $tpl->assign('subscriber', jDao::get($this->dao_subscriber));

        // fil d'arianne
        $navigation = array(
            array('action' => '0', 'params' => array(), 'title' => 'Listes d\'abonnés'),
        );
		$rep->body->assign('navigation', $navigation);

        // marquer le menu
        $rep->body->assign('active_page', 'subscribers');

        // response en ajax
        if($this->param('response')=='ajax') {
            $rep = $this->getResponse('htmlfragment');
            $rep->tplname = 'subscribers_index_ajax';
            $rep->tpl->assign('list_subscribers_lists', $list_subscribers_lists); 
        } else {
            $rep->body->assign('MAIN', $tpl->fetch('subscribers_index')); 
        }

        return $rep;

    }

    // }}}

    // {{{ generateform()

    /**
     * génération d'un formulaire pour site
     *
     * @template    subscribers_generateform
     * @return      html
     */
    public function generateform()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Générer un formulaire d\'abonnement';

        $session = jAuth::getUserSession();

        $tpl = new jTpl();

        // clients
        $customer = jDao::get($this->dao_customer);
        $tpl->assign('customer', $customer->get($session->idcustomer)); 

        // récupérer les listes du client et la liste en cours
        $subscriber_list = jDao::get($this->dao_subscriber_list);
        $list_subscribers_lists = $subscriber_list->getByCustomer($session->idcustomer);
        $tpl->assign('list_subscribers_lists', $list_subscribers_lists); 

        // liste en cours TODO vérif sur le customer ?
        if($this->param('idsubscriber_list')!='') {
            $subscriber_list_infos = $subscriber_list->get($this->param('idsubscriber_list')); 
            $tpl->assign('subscriber_list', $subscriber_list_infos); 
            $tpl->assign('idsubscriber_list', $this->param('idsubscriber_list')); 
        }

        // envoyer l'url du site publique hack crado / supprimer le basePath
        $url_sendui = jUrl::get('public~default:subscribe');
        $url_public = str_replace('admin/', '', $url_sendui);

        // si on a le host
        if(!empty($_SERVER['HTTP_HOST'])) {
            $url_public = 'http://'.$_SERVER['HTTP_HOST'].$url_public;
        }

        // au template
        $tpl->assign('url_public', $url_public);

        // fil d'arianne
        if($this->param('idsubscriber_list')!='') {
            $navigation = array(
                array('action' => 'sendui~subscribers:view', 'params' => array('idsubscriber_list' => $subscriber_list_infos->idsubscriber_list), 'title' => $subscriber_list_infos->name),
                array('action' => '0', 'params' => array(), 'title' => 'Formulaire d\'abonnement'),
            );
        }
		$rep->body->assign('navigation', $navigation);

        // marquer le menu
        $rep->body->assign('active_page', 'subscribers');

        $rep->body->assign('MAIN', $tpl->fetch('subscribers_generateform')); 

        return $rep;

    }

    // }}}

    // {{{ preparelistview()

    /**
     * Préparation du formulaire
     * Eventuellement, reprendre les paramètres d'une liste existence
     *
     * @return      redirect
     */
    public function preparelistview()
    {

        $rep = $this->getResponse('redirect');

        // formulaire
        $form_subscriber_list = jForms::create($this->form_subscriber_list, $this->param('idsubscriber_list'));

        // initialiser un message
        if($this->param('idsubscriber_list')!='') {
            $form_subscriber_list->initFromDao($this->dao_subscriber_list);
            $rep->params = array('idsubscriber_list' => $this->param('idsubscriber_list'));
        }

        $rep->params['from_page'] = $this->param('from_page');

        // redirection vers index
        $rep->action = 'sendui~subscribers:listview';

        return $rep;

    }

    // }}}

    // {{{ listview()

    /**
     * créer/editer une liste
     * 
     * @template    subscriber_listview
     * @return      html
     */
    public function listview()
    {

        $rep = $this->getResponse('html');

        $tpl = new jTpl();

        // récuperer l'instance de formulaire
        if($this->param('idsubscriber_list')!='') {
            $form_subscriber_list = jForms::get($this->form_subscriber_list, $this->param('idsubscriber_list'));
        } else {
            $form_subscriber_list = jForms::get($this->form_subscriber_list);
        }

        // le créer si il n'existe pas
        if ($form_subscriber_list === null) {
            $rep = $this->getResponse('redirect');
            $rep->params = array(
                'idsubscriber_list' => $this->param('idsubscriber_list'),
                'from_page' => $this->param('from_page'),
            );
            $rep->action = 'sendui~subscribers:preparelistview';
            return $rep;
        }

        if($this->param('idsubscriber_list')!='') {
            $subscriber_list = jDao::get($this->dao_subscriber_list);    
            $subscriber_list_infos = $subscriber_list->get($this->param('idsubscriber_list'));
            $tpl->assign('subscriber_list', $subscriber_list_infos);
        }

        $tpl->assign('form_subscriber_list', $form_subscriber_list);
        $tpl->assign('idsubscriber_list', $this->param('idsubscriber_list'));
        $tpl->assign('from_page', $this->param('from_page'));

        // fil d'arianne
        if($this->param('idsubscriber_list')!='') {
            $navigation = array(
                array('action' => 'sendui~subscribers:index', 'params' => array(), 'title' => $subscriber_list_infos->name),
                array('action' => '0', 'params' => array(), 'title' => 'Gérer la liste'),
            );
        } else {
            $navigation[] = array('action' => '0', 'params' => array(), 'title' => 'Créer une liste');
        }
		$rep->body->assign('navigation', $navigation);

        // marquer le menu
        $rep->body->assign('active_page', 'subscribers');

        if($this->param('idsubscriber_list')!='') {
            $rep->title = 'Gérer la liste';
        } else {
            $rep->title = 'Créer une liste';    
        }

        $rep->body->assign('MAIN', $tpl->fetch('subscribers_listview')); 

        return $rep;

    }

    // }}}

    // {{{ listsave()

    /**
     * Sauvegarder les paramètres et aller sur l'ajout de subscribers
     * 
     * @return      redirect
     */
    public function listsave()
    {

        $rep = $this->getResponse('redirect');

        // récupere le form
        if($this->param('idsubscriber_list')!='') {
            $form_subscriber_list = jForms::fill($this->form_subscriber_list, $this->param('idsubscriber_list'));
        } else {
            $form_subscriber_list = jForms::fill($this->form_subscriber_list);
        }
        
        // redirection si erreur
        if (!$form_subscriber_list->check()) {
            $rep->params = array(
                'idsubscriber_list' => $this->param('subscriber_list'),
                'from_page' => $this->param('from_page'),
            );
            $rep->action = 'sendui~subscribers:listview';
            return $rep;
        }

        // enregistrer la nouvelle configuration
        $result = $form_subscriber_list->prepareDaoFromControls($this->dao_subscriber_list);

        // client
        $session = jAuth::getUserSession();
        $result['daorec']->idcustomer = $session->idcustomer;

        if($result['toInsert']) {
            $token = uniqid();
            $result['daorec']->token = $token;
            $idsubscriber_list = $result['dao']->insert($result['daorec']);
        } else {
            $update = $result['dao']->update($result['daorec']);
        }

        // si ok, on redirige sur  la page suivante
        if(!empty($idsubscriber_list) || !empty($update)) {

            // dans le cas update
            if(empty($idsubscriber_list)) {
                $idsubscriber_list = $this->param('idsubscriber_list');
            }

            // détruire le formulaire
            jForms::destroy($this->form_subscriber_list);

            $rep->params = array('idsubscriber_list' => $idsubscriber_list);

            // rediriger vers la page suivante
            if($this->param('from_page')!='') {
                $rep->action = $this->param('from_page');
            } else {
                $rep->action = 'sendui~subscribers:listview';
            }

            return $rep;

        }

    }

    // }}}

    // {{{ view()

    /**
     * lister les abonnés d'une liste
     * 
     * @template    subscriber_view
     * @return      html
     */
    public function view()
    {

        // si aucune idsubscriber_list => retour aux listes
        if($this->param('idsubscriber_list')=='') {
            $rep = $this->getResponse('redirect');
            $rep->action = 'sendui~subscribers:index';
            return $rep;
        }

        $rep = $this->getResponse('html');

        $this->_dataTables($rep);
            
        $tpl = new jTpl();

        // infos sur la liste
        $subscriber_list = jDao::get($this->dao_subscriber_list);
        $subscriber_list_infos = $subscriber_list->get($this->param('idsubscriber_list'));
        $tpl->assign('subscriber_list', $subscriber_list_infos);

        $rep->title = $subscriber_list_infos->name.' - Abonnés à la liste';

        // client
        $session = jAuth::getUserSession();

        // récupérer les abonnés à la liste
        $subscriber = jDao::get($this->dao_subscriber);
        $list_subscribers = $subscriber->getByList($this->param('idsubscriber_list'),$session->idcustomer);

        $tpl->assign('list_subscribers', $list_subscribers);
        $tpl->assign('idsubscriber_list', $this->param('idsubscriber_list'));

        // fil d'arianne
        $navigation = array(
            array('action' => 'sendui~subscribers:index', 'params' => array(), 'title' => $subscriber_list_infos->name),
            array('action' => '0', 'params' => array(), 'title' => 'Abonnés à la liste'),
        );
		$rep->body->assign('navigation', $navigation);

        // marquer le menu
        $rep->body->assign('active_page', 'subscribers');

        $rep->body->assign('MAIN', $tpl->fetch('subscribers_view')); 

        return $rep;

    }

    // }}}

    // {{{ view_list()

    /**
     * lister les abonnés d'une liste
     * 
     * @return      json
     */
    public function view_list()
    {

        // reponse format json
        $rep = $this->getResponse('json');

        // on instancie datatables
        $datas = jClasses::getService('sendui~datatables');

        // les colonnes affichées
        $datas->setColumns(array('status','email','sent_date','date_insert'));

        // l'index de recherche
        $datas->setIndex('idsubscriber');

        // définir le DAO de recherche
        $datas->setDao($this->dao_subscriber);

        // flag de status
        $status_flags = array('blue','green','yellow','red');

        $idsubscriber_list = $this->param('idsubscriber_list');

        $results = array();

        // boucle sur les résultats
        foreach($datas->getResults() as $subscriber) {
            // le status
            $row[0] = '<span class="flag-'.$status_flags[$subscriber->status].'">&nbsp;</span>';
            // l'email
            $email = '<a href="'.jUrl::get('sendui~subscriber:index', array('idsubscriber' => $subscriber->idsubscriber, 'from_page' => 'sendui~subscribers:view', 'idsubscriber_list' => $idsubscriber_list)).'">'.$subscriber->email.'</a>';
            if($subscriber->status==3) {
                $row[1] = '<del>'.$email.'</del>';
            } else {
                $row[1] = $email;
            }
            // date du dernier envoi
            if(!empty($subscriber->sent_date)) {
                //$row['sent_date'] = $subscriber->sent_date|jdatetime:'db_datetime','lang_datetime';
                $row[2] = $subscriber->sent_date;
            } else {
                $row[2] = 'Aucun envoi';
            }
            //$row['date_insert'] = $subscriber->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
            $row[3] = $subscriber->date_insert;
            // actions
            $row[4] = '
                    <a href="'.jUrl::get('sendui~subscriber:prepare', 
                        array('idsubscriber' => $subscriber->idsubscriber, 'from_page' => 'sendui~subscribers:view', 'idsubscriber_list' => $idsubscriber_list)).'" class="table-edit">modifier</a>
                    <a href="'.jUrl::get('sendui~subscribers:deletesubscriber', array('idsubscriber' => $subscriber->idsubscriber, 'idsubscriber_list' => $idsubscriber_list)).'" 
                        class="confirm_action table-delete" title="Êtes-vous sur de vouloir marquer cet abonné comme étant à supprimer ?">supprimer</a>';
            $results[] = $row;
        } 

        $rep->data = $datas->getOutputInfos($results);

        return $rep;

    }

    // }}}

    // {{{ preparesubscriber()

    /**
     * ajouter/modifier un abonné
     * 
     * @return      redirect
     */
    public function preparesubscriber()
    {

        $rep = $this->getResponse('redirect');
        
        // formulaire
        if($this->param('idsubscriber')!='') {
            $form_subscriber = jForms::create($this->form_subscriber, $this->param('idsubscriber'));
        } else {
            $form_subscriber = jForms::create($this->form_subscriber);
        }

        // initialiser un message
        if($this->param('idsubscriber')!='') {
            $form_subscriber->initFromDao($this->dao_subscriber);
        }

        $rep->params = array(
            'idsubscriber' => $this->param('idsubscriber'),
            'idsubscriber_list' => $this->param('idsubscriber_list'),
            'from_page' => $this->param('from_page')
        );


        // redirection vers subscriber
        $rep->action = 'sendui~subscribers:subscriber';

        return $rep;

    }

    // }}}

    // {{{ subscriber()

    /**
     * ajouter/modifier un abonné => formulaire
     * 
     * @template    subscriber_subscriber
     * @return      html
     */
    public function subscriber()
    {

        // si pas de liste, retour
        if($this->param('idsubscriber_list')=='') {
            $rep = $this->getResponse('redirect');
            $rep->action = 'sendui~subscribers:index';
            return $rep;
        }

        $rep = $this->getResponse('html');

        $rep->title = 'Ajouter des abonnés';

        $tpl = new jTpl();

        // récuperer l'instance de formulaire
        if($this->param('idsubscriber')!='') {
            $form_subscriber = jForms::get($this->form_subscriber, $this->param('idsubscriber'));
        } else {
            $form_subscriber = jForms::get($this->form_subscriber);
        }

        // le créer si il n'existe pas
        if ($form_subscriber === null) {
            $rep = $this->getResponse('redirect');
            $rep->params = array(
                'idsubscriber' => $this->param('idsubscriber'),
                'idsubscriber_list' => $this->param('idsubscriber_list'),
                'from_page' => $this->param('from_page')
            );
            $rep->action = 'sendui~subscribers:preparesubscriber';
            return $rep;
        }

        // créer une instance du formulaire d'upload / détruire l'ancienne instance
        $form_subscribers_file = jForms::get($this->form_subscribers_file);
        if ($form_subscribers_file !== null) {
            jForms::destroy($this->form_subscribers_file);
        }
        $form_subscribers_file = jForms::create($this->form_subscribers_file);
        $tpl->assign('form_subscribers_file', $form_subscribers_file);

        // créer une instance de formulaire pour l'ajout par lot
        $form_subscribers_text = jForms::get($this->form_subscribers_text);
        if ($form_subscribers_text !== null) {
            jForms::destroy($this->form_subscribers_text);
        }
        $form_subscribers_text = jForms::create($this->form_subscribers_text);
        $tpl->assign('form_subscribers_text', $form_subscribers_text);

        $tpl->assign('form_subscriber', $form_subscriber);
        $tpl->assign('idsubscriber', $this->param('idsubscriber'));
        $tpl->assign('idsubscriber_list', $this->param('idsubscriber_list'));
        $tpl->assign('from_page', $this->param('from_page'));

        // infos sur la liste TODO check avec le customer
        if($this->param('idsubscriber_list')!='') {
            $subscriber_list = jDao::get($this->dao_subscriber_list);
            $subscriber_list_infos = $subscriber_list->get($this->param('idsubscriber_list'));
            $tpl->assign('subscriber_list', $subscriber_list_infos);
        }

        // tester si le dossier upload est accessible en écriture
        if(!is_writable(UPLOAD_DIR_PATH)) {
            $tpl->assign('no_writable', UPLOAD_DIR_PATH);
        }
    
        // marquer le menu
        $rep->body->assign('active_page', 'subscribers');

        // fil d'arianne
        if($this->param('idsubscriber_list')!='') {
            $navigation = array(
                array('action' => 'sendui~subscribers:index', 'params' => array(), 'title' => 'Listes d\'abonnés'),
                array('action' => 'sendui~subscribers:view', 'params' => array('idsubscriber_list' => $subscriber_list_infos->idsubscriber_list), 'title' => $subscriber_list_infos->name),
                array('action' => '0', 'params' => array(), 'title' => 'Ajouter des abonnés'),
            );
        }
		$rep->body->assign('navigation', $navigation);

        $rep->body->assign('MAIN', $tpl->fetch('subscribers_subscriber')); 

        return $rep;

    }

    // }}}

    // {{{ subscribersave()

    /**
     * ajouter/modifier un abonné => sauvegarder les modifications
     * 
     * @template    subscribersave
     * @return      redirect
     */
    public function subscribersave()
    {

        // récupere le form
        if($this->param('idsubscriber')!='') {
            $form_subscriber = jForms::fill($this->form_subscriber, $this->param('idsubscriber'));
        } else {
            $form_subscriber = jForms::fill($this->form_subscriber);
        }

        $rep = $this->getResponse('redirect');

        // redirection si erreur
        if (!$form_subscriber->check()) {
            $rep->params = array(
                'idsubscriber' => $this->param('idsubscriber'),
                'idsubscriber_list' => $this->param('idsubscriber_list'),
                'from_page' => $this->param('from_page')
            );
            $rep->action = 'sendui~subscribers:subscriber';
            return $rep;
        }

        // utilisateur
        $session = jAuth::getUserSession();

        // enregistrer 
        $result = $form_subscriber->prepareDaoFromControls($this->dao_subscriber);

        if($result['toInsert']) {

            // client pour la sécurité
            $result['daorec']->idcustomer = $session->idcustomer;

            // si on a idsubscriber_list, enregistrer dans la bonne liste
            if($this->param('idsubscriber_list')!='') {
                $result['daorec']->idsubscriber_list = $this->param('idsubscriber_list');
            }

            $result['dao']->insert($result['daorec']);

            // identifiant du nouvel abonné
            $idsubscriber = $result['daorec']->idsubscriber;

        } else {
            $update = $result['dao']->update($result['daorec']);
        }

        // si ok, on redirige sur  la page suivante
        if(!empty($idsubscriber) || !empty($update)) {

            // dans le cas update
            if(empty($idsubscriber)) {
                $idsubscriber = $this->param('idsubscriber');
            }

            // détruire le formulaire
            jForms::destroy($this->form_subscriber);

            $rep->params = array(
                'idsubscriber' => $idsubscriber,
                'idsubscriber_list' => $this->param('idsubscriber_list'),
            );

            // rediriger vers la page suivante
            if($this->param('from_page')!='') {
                $rep->action = $this->param('from_page');
            } else {
                $rep->action = 'sendui~subscribers:subscriber';
            }

            return $rep;

        }

    }

    // }}}

    // {{{ subscribers_textsave()

    /**
     * ajouter des abonnés depuis le champ text
     * 
     * @template    subscribersave
     * @return      redirect
     */
    public function subscribers_textsave()
    {

        // récupere le form
        $form_subscribers_text = jForms::fill($this->form_subscribers_text);

        $rep = $this->getResponse('redirect');

        // redirection si erreur
        if ($form_subscribers_text!==null && !$form_subscribers_text->check()) {
            $rep->params = array(
                'idsubscriber_list' => $this->param('idsubscriber_list'),
                'from_page' => $this->param('from_page')
            );
            $rep->anchor = 'subscribers-text';
            $rep->action = 'sendui~subscribers:subscriber';
            return $rep;
        }

        // le champ text
        $subscribers_text = $this->param('subscribers');

        // détruire le formulaire
        jForms::destroy($this->form_subscribers_text);

        if($results_subscribers_save = $this->subscribers_save($subscribers_text)) {
            return $this->subscribers_save_response('subscribers-text',$results_subscribers_save);
        }

        $rep->params = array(
            'idsubscriber_list' => $this->param('idsubscriber_list'),
        );

        // rediriger vers la page suivante
        if($this->param('from_page')!='') {
            $rep->action = $this->param('from_page');
        } else {
            $rep->action = 'sendui~subscribers:subscriber';
        }

        return $rep;


    }

    // }}}

    // {{{ subscribers_filesave()

    /**
     * ajouter des abonnés depuis un fichier CSV
     * 
     * @template    subscribersave
     * @return      redirect
     */
    public function subscribers_filesave()
    {

        $rep = $this->getResponse('redirect');

        // récupere le form
        $form_subscribers_file = jForms::fill($this->form_subscribers_file);

        // redirection si erreur
        if (!$form_subscribers_file->check()) {
            $rep->params = array(
                'idsubscriber_list' => $this->param('idsubscriber_list'),
                'from_page' => $this->param('from_page')
            );
            $rep->action = 'sendui~subscribers:subscriber';
            $rep->anchor = 'subscribers-file';
            return $rep;
        }

        $file_path = JELIX_APP_WWW_PATH.'admin/upload/';
        $file_name = uniqid();

        // sauvegarde le ficher
        if($form_subscribers_file->saveFile('file_subscribers', $file_path, $file_name)) {

            // ouvrir le ficher
            $file_data = file_get_contents($file_path.$file_name);

            // sauvegarde
            if(!empty($file_data)) {
                // si ok, affiche le résultat
                $results_subscribers_save = $this->subscribers_save($file_data);
                return $this->subscribers_save_response('subscribers-file',$results_subscribers_save);
            }

            
        }

    }

    // }}}

    // {{{ subscribers_save_response()

    /**
     * afficher les résultats de l'ajout d'abonnés
     * 
     * @template    subscribers_save_response
     * @return      html
     */
    protected function subscribers_save_response($type,$results)
    {

        // le nombre d'abonnés ajoutés + un tableau avec les erreurs + choix ajouter autre ou retour liste 
        $rep = $this->getResponse('html');

        $rep->title = 'Ajout d\'abonnés à la liste';

        $tpl = new jTpl();

        // récupérer la liste TODO uniquement du client
        $subscriber_list = jDao::get($this->dao_subscriber_list);
        $subscriber_list_infos = $subscriber_list->get($this->param('idsubscriber_list'));
        $tpl->assign('subscriber_list', $subscriber_list_infos);
        $tpl->assign('idsubscriber_list', $this->param('idsubscriber_list'));

        // envoyer la liste des erreurs et le nb de OK
        $tpl->assign('results', $results);
        $tpl->assign('nb_success', count($results['success']));
        $tpl->assign('nb_error', count($results['error']));

        // fil d'arianne
        if($this->param('idsubscriber_list')!='') {
            $navigation = array(
                array('action' => 'sendui~subscribers:index', 'params' => array(), 'title' => 'Listes d\'abonnés'),
                array('action' => 'sendui~subscribers:view', 'params' => array('idsubscriber_list' => $subscriber_list_infos->idsubscriber_list), 'title' => $subscriber_list_infos->name),
                array('action' => 'sendui~subscribers:subscriber', 'params' => array('idsubscriber_list' => $subscriber_list_infos->idsubscriber_list), 'title' => 'Ajouter des abonnés'),
                array('action' => '0', 'params' => array(), 'title' => 'Résultat'),
            );
        }
		$rep->body->assign('navigation', $navigation);

        $rep->body->assign('MAIN', $tpl->fetch('subscribers_save_response')); 

        return $rep;

    }

    // }}}

    // {{{ subscribers_save()

    /**
     * ajouter des abonnés : méthode commune
     * 
     * @return  bool
     */
    protected function subscribers_save($subscribers_text)
    {

        // variable
        $subscribers_tabs_list_error = array();
        $subscribers_tabs_list_success = array();

        // session avec idcustomer
        $session = jAuth::getUserSession();

        if(empty($subscribers_text)) {
            return array(
                'success' => $subscribers_tabs_list_success,
                'error' => $subscribers_tabs_list_error,
            );
        }

        // explode par ligne
        $subscribers_tabs = explode("\n", $subscribers_text);

        $keyval = array('email','fullname','lastname','firtsname');

        // découpage par point virgule et chaque champ avec des doubles quotes
        if(is_array($subscribers_tabs) && !empty($subscribers_tabs)) {

            // on parcours le tableau
            foreach($subscribers_tabs as $k=>$v) {

                // on exploide chaque ligne sur les points virgules
                $subscriber_value = explode(';', $v);

                // on parcour chaque ligne
                if(is_array($subscriber_value) && !empty($subscriber_value)) {

                    $subscriber_value_final = array();
                    $i = 0;

                    foreach($subscriber_value as $kf=>$f) {
                        
                        // attention au espace
                        $f = trim($f);

                        if(!empty($f)) {
                            $f = str_replace('"','',$f);
                        }

                        // la première colonne DOIT être un email => sinon, colonne dans l'erreur
                        if($i==0  && !filter_var($f, FILTER_VALIDATE_EMAIL)) {
                            $subscriber_value['error'] = 'La première colonne n\'est pas un email valide';
                            $subscribers_tabs_list_error[] = $subscriber_value;
                            break;
                        }

                        // une des colonnes suivantes contient un mail
                        if($i>0 && filter_var($f, FILTER_VALIDATE_EMAIL)) {
                            $subscriber_value['error'] = 'Seule la première colonne doit contenir un email valide';
                            $subscribers_tabs_list_error[] = $subscriber_value;
                            break;
                        }

                        if(!empty($f) && !empty($keyval[$i])) {
                            $subscriber_value_final[$keyval[$i]] = $f;
                            $i++;
                        }

                        

                    }

                    // on ajoute le array de l'abonné dans le array général
                    if(!empty($subscriber_value_final)) {
                        $subscribers_tabs_list[] = $subscriber_value_final;
                    }

                }
            }
        }

        // on a une liste
        if(!empty($subscribers_tabs_list)) {
        
            $subscriber = jClasses::getService('sendui~subscriber');

            // on parcours et on ajoute
            foreach($subscribers_tabs_list as $k=>$s) {

                // ajouter des infos
                $s['idcustomer'] = $session->idcustomer;
                $s['subscribe_from'] = 'internal';

                // TODO : checker la liste !
                if($subscriber->subscribe($s,$this->param('idsubscriber_list'),null,true)) {
                    $subscribers_tabs_list_success[] = $s;
                } else  {
                    $s['error'] = $subscriber->getSubscriberError('debug');
                    $s[0] = $s['email'];
                    $subscribers_tabs_list_error[] = $s;
                }

            }

        }

        return array(
            'success' => $subscribers_tabs_list_success,
            'error' => $subscribers_tabs_list_error,
        );


    }

    // }}}

    // {{{ listpurge()

    /**
     * Supprimer définitivement les abonnés marqués comme "supprimé"
     * 
     * @return      redirect
     */
    public function listpurge()
    {

        $rep = $this->getResponse('redirect');

        // on n'a pas de idsubscriber_list ?
        if($this->param('idsubscriber_list')=='') {
            $rep->action = 'sendui~subscribers:index';
            return $rep;
        } else {
            $rep->params['idsubscriber_list'] = $this->param('idsubscriber_list');
        }

        // supprimer les abonnés de cette liste qui sont marqué comme "supprimé"
        $subscriber = jDao::get($this->dao_subscriber);
        $nb_deleted_subscribers = $subscriber->purgeSubscriberByList($this->param('idsubscriber_list'));

        $rep->params['nb_deleted_subscribers'] = $nb_deleted_subscribers;
        
        // redirection 
        if ($this->param('from_page')) {
            $rep->action = $this->param('from_page');
        } else {
            $rep->action = 'sendui~subscribers:view';
        }

        return $rep;
        
    }

    // }}}

    // {{{ listdelete()

    /**
     * Supprimer une liste
     *
     * @return      redirect
     */
    public function listdelete()
    {

        $rep = $this->getResponse('redirect');

        if($this->param('idsubscriber_list')!='') {
            
            // TODO tester aussi sur le customer pour la sécurité ET double vérification
            $subscriber_list = jDao::get($this->dao_subscriber_list);

            if($subscriber_list->delete($this->param('idsubscriber_list'))) {
                $rep->params = array('delete' => true);
            }
        
        }

        $rep->action = 'sendui~subscribers:index';
        return $rep;

    }

    // }}}
   
    // {{{ deletesubscriber()

    /**
     * Marquer un utilisateur à supprimer
     *
     * @return      redirect
     */
    public function deletesubscriber()
    {

        $rep = $this->getResponse('redirect');

        if($this->param('idsubscriber')!='') {
            
            // TODO tester aussi sur le customer pour la sécurité ET double vérification
            $subscriber = jDao::get($this->dao_subscriber);

            if($subscriber->changeStatusByIdsubscriber($this->param('idsubscriber'),3)) {
                $rep->params['delete_subscriber'] = true;
            }

            // id du user
            $rep->params['idsubscriber'] = $this->param('idsubscriber');
        
        }

        $rep->params['idsubscriber_list'] = $this->param('idsubscriber_list');

        if($this->param('from_page')!='') {
            $rep->action = $this->param('from_page');
        } else {
            if($this->param('idsubscriber_list')!='') {
                $rep->action = 'sendui~subscribers:view';
            } else {
                $rep->action = 'sendui~subscribers:index';
            }
        }

        return $rep;

    }

    // }}}

    // {{{ purgesubscriber()

    /**
     * Supprimer définitivement un utilisateur
     *
     * @return      redirect
     */
    public function purgesubscriber()
    {

        $rep = $this->getResponse('redirect');

        if($this->param('idsubscriber')!='') {
            
            // TODO tester aussi sur le customer pour la sécurité ET double vérification
            $subscriber = jDao::get($this->dao_subscriber);

            if($subscriber->delete($this->param('idsubscriber'))) {
                $rep->params['delete_subscriber'] = true;
            }

            // id du user
            $rep->params['idsubscriber'] = $this->param('idsubscriber');
        
        }

        $rep->params['idsubscriber_list'] = $this->param('idsubscriber_list');

        if($this->param('from_page')!='') {
            $rep->action = $this->param('from_page');
        } else {
            if($this->param('idsubscriber_list')!='') {
                $rep->action = 'sendui~subscribers:view';
            } else {
                $rep->action = 'sendui~subscribers:index';
            }
        }

        return $rep;

    }

    // }}}

    // {{{ exportcsv()

    /**
     * Exporter la liste des utilisateurs au format CSV
     *
     * @return      redirect
     */
    public function exportcsv()
    {

        // si pas de idsubscriber_list => retour
        if($this->param('idsubscriber_list')=='') {
            $rep = $this->getResponse('redirect');
            $rep->action = 'sendui~subscribers:index';
            return $rep;
        }

        // infos sur la liste
        $subscriber_list = jDao::get($this->dao_subscriber_list);
        $subscriber_list_infos = $subscriber_list->get($this->param('idsubscriber_list'));

        // client
        $session = jAuth::getUserSession();

        // récupérer les abonnés à la liste
        $subscriber = jDao::get($this->dao_subscriber);
        $list_subscribers = $subscriber->getByList($this->param('idsubscriber_list'),$session->idcustomer);
        
        // on renvoit un fichier  CSV
        $rep = $this->getResponse('binary');
        $rep->outputFileName = 'list_'.$this->params('idsubscriber_list').'_'.date('YmdHis').'.csv';
        $rep->mimeType = 'text/csv';
        $rep->doDownload = true;

        foreach($list_subscribers as $subscriber) {
            $datas[] = join(';', (array)$subscriber);
        }

        $rtl = "\n";

        $rep->content = join($rtl, $datas);

        return $rep;

    }

}

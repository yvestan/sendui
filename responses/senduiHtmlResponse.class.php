<?php
/**
* @package   sendui
* @subpackage 
* @author    Yves Tannier [grafactory.net]
* @copyright 2009 Yves Tannier
* @link      http://www.grafactory.net/sendui
* @license   http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
*/

require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');

class senduiHtmlResponse extends jResponseHtml {

    public $bodyTpl = 'sendui~main';

    function __construct() {

        parent::__construct();

        // ajouter une feuille de style 
        foreach(array('reset','style') as $k) {
            $this->addCSSLink($GLOBALS['gJConfig']->path_app['sendui'].'/css/'.$k.'.css');
        }

        // jquery-ui
        $this->addCSSLink($GLOBALS['gJConfig']->path_app['sendui'].'/css/start/jquery-ui-1.7.2.custom.css');

        // ajouter les javascript jquery
        $this->addJSLink($GLOBALS['gJConfig']->path_app['sendui'].'/js/jquery-1.3.2.min.js');
        $this->addJSLink($GLOBALS['gJConfig']->path_app['sendui'].'/js/jquery-ui-1.7.2.custom.min.js');

    }

    protected function doAfterActions() {

        // utilisateur connecté
        $session = jAuth::getUserSession();
        $this->body->assign('session', $session);

        $this->body->assignIfNone('MAIN','<p>no content</p>');
    }

}

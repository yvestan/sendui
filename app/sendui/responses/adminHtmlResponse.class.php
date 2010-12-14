<?php
/**
* @package   sendui
* @subpackage 
* @author    Yves Tannier [grafactory.net]
* @copyright 2009 Yves Tannier
* @link      http://www.grafactory.net/sendui
* @license   http://www.grafactory.net/sendui/licence MIT Licence
*/

require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');

class adminHtmlResponse extends jResponseHtml {

    public $bodyTpl = 'master_admin~main';

    function __construct() {
        parent::__construct();

        // Include your common CSS and JS files here
        $this->addCSSLink($GLOBALS['gJConfig']->urlengine['jelixWWWPath'].'design/master_admin.css');
    }

    protected function doAfterActions() {
        // Include all process in common for all actions, like the settings of the
        // main template, the settings of the response etc..
        $this->title .= ($this->title !=''?' - ':'').' Administration';
        $this->body->assignIfNone('selectedMenuItem','');
        $this->body->assignZone('MENU','master_admin~admin_menu', array('selectedMenuItem'=>$this->body->get('selectedMenuItem')));
        $this->body->assignZone('INFOBOX','master_admin~admin_infobox');
        $this->body->assignIfNone('MAIN','');
        $this->body->assignIfNone('adminTitle','');
        $this->body->assign('user', jAuth::getUserSession());
    }

}

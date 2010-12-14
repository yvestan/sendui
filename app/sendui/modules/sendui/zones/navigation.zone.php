<?php
 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Zone pour le fil d'arianne
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class navigationZone extends jZone {
 
    // templates
    protected $_tplname='navigation.inc';
 
    // méthode principales
    protected function _prepareTpl(){

        // parametre du fil d'arianne
        if($this->getParam('valuesNavigation')) {
            $valuesNavigation = $this->getParam('valuesNavigation');
            $this->_tpl->assign('valuesNavigation', $valuesNavigation);
        }

    }

}
?>

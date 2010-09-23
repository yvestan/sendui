<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Zone pour l'affichage des étapes
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class stepsZone extends jZone {
     
    // templates
    protected $_tplname = 'steps.inc';

    // méthode prepare template (parametre step obligatoire)
    protected function _prepareTpl(){

        $steps = array(
            array('num' => 1, 'name' => 'Réglages'),
            array('num' => 2, 'name' => 'Composition'),
            array('num' => 3, 'name' => 'Destinataires'),
            array('num' => 4, 'name' => 'Vérification'),
            array('num' => 5, 'name' => 'Envoi'),
        );

        foreach($steps as $k=>$s) {
            if($s['num']<$this->param('step')) {
                $steps[$k]['status'] = 'default';
                $steps[$k]['status_text'] = null;
            } elseif($s['num']==$this->param('step')) {
                $steps[$k]['status'] = 'active';
                $steps[$k]['status_text'] = null;
            } else {
                $steps[$k]['status'] = 'disabled';
                $steps[$k]['status_text'] = '-disabled';
            }
        }

        $this->_tpl->assign('steps_description',$steps);

    }

}
?>

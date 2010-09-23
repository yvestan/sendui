<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Classe de gestion des thèmes
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class Themes {

    // chemin vers les thèmes
    protected $themes_path;

    // {{{ getThemes()

   /** Liste des themes
    *
    * @access   public
    * @return   array
    */
    public function getThemes()
    {

        $this->themes_path = JELIX_APP_WWW_PATH.'css/themes/';

        // on liste
        $themes = scandir($this->themes_path);

        // on zappe les répertoires . et ..
        unset($themes[0]);
        unset($themes[1]);

        return $themes;

    }

    // }}}

}
?>

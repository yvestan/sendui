<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Entête de l'installation
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');

class installHtmlResponse extends jResponseHtml {

    // template principal
    public $bodyTpl = 'sendui~install';

    // base du site
    protected $base_path;

    // {{{ __construct

    /**
     * Constructeur feuille de style et js de base
     *
     */
    public function __construct() {

        parent::__construct();

        $this->base_path = $GLOBALS['gJConfig']->urlengine['basePath'];

        // ajouter une feuille de style 
        foreach(array('reset','style') as $k) {
            $this->addCSSLink($this->base_path.'css/'.$k.'.css');
        }

        // ajouter les javascript jquery
        $this->addJSLink($this->base_path.'js/jquery-1.3.2.min.js');
        $this->addJSLink($this->base_path.'js/jquery-ui-1.7.2.custom.min.js');
        $this->addJSLink($this->base_path.'js/button.js');
        $this->addJSLink($this->base_path.'js/function.js');

        $this->addHeadContent('<link rel="icon" type="image/png" href="'.$this->base_path.'favicon.ico" />');

    }

    // }}}

    // {{{ doAfterActions()

    /**
     * Que faire à la fin de l'action ? contenu alternatif
     *
     * @template    send_index
     * @return      html
     */
    protected function doAfterActions() {

        // default
        $style_ui = 'hot-sneaks';

        $this->addCSSLink($this->base_path.'css/themes/'.$style_ui.'/jquery-ui-1.7.2.custom.css');

        // si pas de contenu
        $this->body->assignIfNone('MAIN','<p>Cette page n\'existe pas</p>');

    }

    // }}}

}

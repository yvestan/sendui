<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Entête de la partie publique
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

class publicHtmlResponse extends jResponseHtml {

    // template principal
    public $bodyTpl = 'public~main';

    // {{{ __construct

    /**
     * Constructeur feuille de style et js de base
     *
     */
    public function __construct() {

        parent::__construct();

        $base_path = $GLOBALS['gJConfig']->urlengine['basePath'];

        // ajouter une feuille de style 
        foreach(array('reset','style') as $k) {
            $this->addCSSLink($base_path.'css/'.$k.'.css');
        }

    }

    // }}}

    // {{{ doAfterActions()

    /**
     * Que faire à la fin de l'action ? contenu alternatif
     *
     * @template    send_index
     * @return      html
     */
    protected function doAfterActions() { $this->body->assignIfNone('MAIN','<p>no content</p>'); }

    // }}}

}

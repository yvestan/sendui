<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 *
 * Entête simple / par exemple pour la preview
 *
 * @package   sendui
 * @subpackage 
 * @author    Yves Tannier [grafactory.net]
 * @copyright 2009 Yves Tannier
 * @link      http://www.grafactory.net/sendui
 * @license   http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
*/

require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');

class simpleHtmlResponse extends jResponseHtml {

    // template principal pour la preview
    public $bodyTpl = 'sendui~simple_main';

    // {{{ __construct

    /**
     * Constructeur ultra simple
     *
     */
    public function __construct() {
        parent::__construct();
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
        $this->body->assignIfNone('MAIN','<p>no content</p>');
    }

    // }}}
}

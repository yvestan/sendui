<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Barre de progression progression
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       yves tannier [grafactory.net]
 * @copyright    2009 yves tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence gpl gnu public licence
 * @version      0.1.0
 */

class progress {

    // dao
    protected $dao_subscriber = 'common~subscriber';

    // {{{ view()

    /** Voir la progression
     * 
     */
    public function view(&$rep, $idmessage, $nb_subscribers=null)
    {

        // les destinataires trouvé via message_subscriber_list
        if(empty($nb_subscribers)) {
            $subscriber = jDao::get($this->dao_subscriber);
            $subscribers_infos = $subscriber->countMessageSubscribers($idmessage,1); 
            $nb_subscribers = $subscribers_infos->nb;
        }

        $rep->addJSLink($GLOBALS['gJConfig']->urlengine['basePath'].'js/progressbar/jquery.progressbar.min.js');

        // ajoute les infos
        $js_more = '
            var idmessage = '.$idmessage.';
            var link_status = \''.jUrl::get('sendui~send:process', array('idmessage' => $idmessage)).'\';
            var nb_subscribers = '.$nb_subscribers.';
            var base_path = \''.$GLOBALS['gJConfig']->urlengine['basePath'].'\';
        ';
        $rep->addJSCode($js_more);
        $rep->addHeadContent('<script type="text/javascript" src="'.$GLOBALS['gJConfig']->urlengine['basePath'].'js/state.js" ></script>');

    }

    // }}}

}
?>

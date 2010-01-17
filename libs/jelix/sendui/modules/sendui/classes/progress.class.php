<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * progression
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
     * @return  int  retourne le pid
     * @param   string  $cmd   la commande a lancer 
     * @param   int     $priority   la priorité du process
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

        $rep->addJSLink($GLOBALS['gJConfig']->path_app['sendui'].'/js/progressbar/jquery.progressbar.min.js');

        // ajoute les infos
        $js_more = '
            var idmessage = '.$idmessage.';
            var link_status = \''.jUrl::get('sendui~send:process', array('idmessage' => $idmessage)).'\';
            var nb_subscribers = '.$nb_subscribers.';
            var path_app = \''.$GLOBALS['gJConfig']->path_app['sendui'].'\';
        ';
        $rep->addJSCode($js_more);
        $rep->addHeadContent('<script type="text/javascript" src="'.$GLOBALS['gJConfig']->path_app['sendui'].'/js/state.js" ></script>');

    }

    // }}}

    // {{{ isprocessrunning()

    /** voir l'état du process
     * 
     * @return int
     * @param   int     $pid    le pid
     *
     */
    public static function isprocessrunning($pid)
    {
        exec('ps '.$pid, $process_state);
        return count($process_state) >= 2;
    }

    // }}}

    // {{{ stopprocess()

    /** stopper le processus
     * 
     * @return  bool
     * @param   int     $pid    le pid du process
     *
     */
    public static function stopprocess($pid)
    {
        exec('kill -9 '.$pid);
        return true;
    }

    // }}}

}
?>

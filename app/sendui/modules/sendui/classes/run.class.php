<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Classe de gestion des process systèmes
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class Run {

    // {{{ backgroundRun()

    /** Lancer le processus en tache de fond
     * 
     * @return  int  retourne le pid
     * @param   string  $cmd   La commande a lancer 
     * @param   int     $priority   La priorité du process
     *
     */
    public static function runBackground($cmd, $priority=null)
    {
        if($priority) {
            $pid = shell_exec('nohup nice -n '.$priority.' '.$cmd.' 2> /dev/null & echo $!');
        } else {
            $pid = shell_exec('nohup '.$cmd.' 2> /dev/null & echo $!');
        }
        return $pid;
    }

    // }}}

    // {{{ isProcessRunning()

    /** Voir l'état du process
     * 
     * @return int
     * @param   int     $pid    Le PID
     *
     */
    public static function isProcessRunning($pid)
    {
        exec('ps '.$pid, $process_state);
        return count($process_state) >= 2;
    }

    // }}}

    // {{{ stopProcess()

    /** Stopper le processus
     * 
     * @return  bool
     * @param   int     $pid    le pid du process
     *
     */
    public static function stopProcess($pid)
    {
        exec('kill -9 '.$pid);
        return true;
    }

    // }}}

}
?>

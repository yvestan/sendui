<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Envoyer le message
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class sendCtrl extends jController {

    // dao process et message
    protected $dao_process = 'common~process';
    protected $dao_message = 'common~message';

    // abonnés
    protected $dao_subscriber = 'common~subscriber';

    // {{{ index()

    /**
     * Demander la confirmation d'envoi du message
     *
     * @template    send_index
     * @return      html
     */
    public function index()
    {

        $rep = $this->getResponse('html');

        $rep->title = 'Confirmer l\'envoi du message';
        
        $tpl = new jTpl();

        $tpl->assign('idmessage', $this->param('idmessage')); 

        $rep->body->assign('MAIN', $tpl->fetch('send_index')); 

        return $rep;
        
    }

    // }}}

    // {{{ cancel()

    /**
     * Annuler la demande et revenir sur le status 1
     *
     * @return      redirect
     */
    public function cancel() { return $this->changeStatus(0); }

    // }}}

    // {{{ cancelall()

    /**
     * Annuler la demande et passer en status 5
     *
     * @return      redirect
     */
    public function cancelall() { return $this->changeStatus(5); }

    // }}}

    // {{{ stop()

    /**
     * Stopper l'envoi
     *
     * @return      redirect
     */
    public function stop()
    {

        $rep = $this->getResponse('redirect');

        // récupérer le pid dans la table process
        $process = jDao::get($this->dao_process);
        $last_process = $process->getLast($this->param('idmessage'));

        // stop !
        if(!empty($last_process->pid)) {

            $run = jClasses::getService('sendui~run');
            $run->stopProcess($last_process->pid);

            // ici on log le pid et l'id du message
            jLog::log('['.$last_process->pid.']['.$this->param('idmessage').'][STOP]  Arrêt demandé via GUI','process');

        } else {
            jLog::log('[0]['.$this->param('idmessage').'][FATAL] Arrêt demandé via GUI impossible : aucun PID','process');
        }
        
        // on mets le champs status sur 3
        $message = jDao::get($this->dao_message);
        $message->setStatus($this->param('idmessage'),3);

        if($this->param('from_page')!='') {
            $rep->action = $this->param('from_page');    
        } else {
            $rep->action = 'sendui~send:index';
        }

        $rep->params = array('idmessage' => $this->param('idmessage'));

        return $rep;

    }

    // }}}

    // {{{ start()

    /**
     * Lancer l'envoi
     *
     * @return      redirect
     */
    public function start()
    {

        $rep = $this->getResponse('redirect');

        $cmd_more = null;

        $message = jDao::get($this->dao_message);
        $message_infos = $message->get($this->param('idmessage'));

        // si le compteur du message est à zero, force le reset
        if($message_infos->count_recipients==0) {
            $cmd_more .= '--forcereset';
        }

        // lancer
        $run = jClasses::getService('sendui~run');
        $cmd = JELIX_APP_PATH.'/scripts/send --idmessage '.((int)$this->param('idmessage')).' '.$cmd_more.' >/dev/null';
        $pid = $run->runBackground($cmd);

        // ici on log le pid et l'id du message
        jLog::log('['.$pid.']['.$this->param('idmessage').'] '.$cmd, 'process');

        // mettre le status à 2
        if(!empty($pid)) {
            $message->setStatus($this->param('idmessage'),2);
        }
        
        if($this->param('from_page')!='') {
            $rep->action = $this->param('from_page');    
        } else {
            $rep->action = 'sendui~send:index';
        }

        $rep->params = array('idmessage' => $this->param('idmessage'));

        return $rep;

    }

    // }}}

    // {{{ process()

    /**
     * Voir l'état d'avancement => réponse json
     *
     * @return      json
     */
    public function process()
    {

        $rep = $this->getResponse('json');
        $idmessage = $this->param('idmessage');

        $process = jDao::get($this->dao_process);
        $last_process = $process->getLast($idmessage);

        if(!empty($last_process->log)) {
            $rep->data = array(
                'log' => $last_process->log,
                'idprocess' => $last_process->idprocess,
                'counter' => $last_process->counter,
                'lastproc' => false,
            );
        } else {
            $rep->data = array(
                'log' => null,
                'idprocess' => 0,
                'counter' => 0,
                'lastproc' => true,
            );
        }

        return $rep;
        
    }

    // }}}

    // {{{ test()

    /**
     * Lancer l'envoi d'un test
     *
     * @return      redirect
     */
    public function test()
    {

        $rep = $this->getResponse('redirect');

        if($this->param('from_page')!='') {
            $rep->action = $this->param('from_page');    
        } else {
            $rep->action = 'sendui~send:index';
        }

        $rep->params = array('idmessage' => $this->param('idmessage'));

        if($this->param('emails_test')=='') {
            $rep->params['error'] = 'no_email';
            return $rep;
        }

        // récupérer les emails
        $emails_tab = explode(',', $this->param('emails_test'));

        $emails = array();

        foreach($emails_tab as $e) {
            if(filter_var($e, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $e;
            }
            if(count($emails)>=5) break;
        }

        if(empty($emails)) {
            $rep->params['error'] = 'no_email';
            return $rep;
        }

        // les remetttres dans une string
        $emails_string = join(',', $emails);

        $cmd_more = null;

        $message = jDao::get($this->dao_message);
        $message_infos = $message->get($this->param('idmessage'));

        $cmd_more = '--test '.$emails_string;

        // lancer
        $run = jClasses::getService('sendui~run');
        $cmd = JELIX_APP_PATH.'/scripts/send --idmessage '.((int)$this->param('idmessage')).' '.$cmd_more.' >/dev/null';
        $pid = $run->runBackground($cmd);

        if(!empty($pid)) {
            $rep->params['success'] = count($emails);    
        }

        // ici on log le pid et l'id du message
        jLog::log('['.trim($pid).']['.$this->param('idmessage').'] '.$cmd, 'process');
        
        return $rep;

    }

    // }}}

    // {{{ changeStatus()

    /**
     * Changer le status
     *
     * @return  redirect
     */
    protected function changeStatus($status)
    {

        $rep = $this->getResponse('redirect');

        // on mets le champs status sur 3
        $message = jDao::get($this->dao_message);
        $message->setStatus($this->param('idmessage'),$status);

        if($this->param('from_page')!='') {
            $rep->action = $this->param('from_page');    
        } else {
            $rep->action = 'sendui~send:index';
        }

        $rep->params = array('idmessage' => $this->param('idmessage'));

        return $rep;

    }

    // }}}

}

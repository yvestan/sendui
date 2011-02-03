<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Envoyer le message en ligne de commande (send)
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class sendingCtrl extends jControllerCmdLine {

    // message
    protected $dao_message = 'common~message';

    // log
    protected $log_file = 'process';

    // silencieux ?
    protected $verbose = true;

    // retour de ligne
    protected $n = "\n";

    // classe batch
    protected $class_batch = 'sendui~batch';

    // abonnés
    protected $dao_subscriber = 'common~subscriber';

    // le pid
    protected $pid = 0;

    // le message
    protected $idmessage = 0;

    // log
    protected $dao_process = 'common~process';

    // uniquement pour tester la progression sans envoyer les messages
    protected $no_send = false;

    // module sans authentification
    public $pluginParams = array(
      '*'=>array('auth.required'=>false)
    );

    /**
    * Options to the command line
    *  'method_name' => array('-option_name' => true/false)
    * true means that a value should be provided for the option on the command line
    */
    protected $allowed_options = array(
        'index' => array(
            '--idmessage' => true,
            '-i' => true,
            '--test' => true,
            '-t' => true,
            '--reset' => false,
            '-r' => false,
            '--forcereset' => false,
            '-f' => false,
            '--pid' => true,
            '-p' => true,
        ),
    );

    public $help = array(
        'index' => '
            -i --idmessage : identifiant du message
            -r --reset : remettre à zéro le flag d\'envoi (champ sent)
            -f --forcereset : force une remise à zero avant un envoi (champ sent)
            -p --pid : le pid du processus
            help : cette aide
        '
    );

    /**
     * Parameters for the command line
     * 'method_name' => array('parameter_name' => true/false)
     * false means that the parameter is optionnal. All parameters which follow an optional parameter
     * is optional
     */
    protected $allowed_parameters = array();
     
    // {{{ index()

    /**
     * Envoyer
     *
     * @template    send_index
     * @return      html
     */
    public function index() 
    {

        // interceter le sigterm et le sigint si pctnl signal existe
        /*if(!function_exists('pcntl_signal')) {
            declare(ticks = 1);
            pcntl_signal(SIGTERM, array($this, 'signalHandler'));
            pcntl_signal(SIGINT, array($this,'signalHandler'));
        }*/

        $rep = $this->getResponse(); 

        $idmessage = $this->option('--idmessage');
        if(empty($idmessage)) {
            $idmessage = $this->option('-i');
        }

        // il faut obligatoirement l'identifiant du message
        if(empty($idmessage)) {
            $rep->addContent('Vous devez préciser l\'identifiant du message'.$this->n);
            return $rep;
        } else {
            $this->idmessage = $idmessage;    
        }

        // message de test ?
        $test = $this->option('--test');
        if(empty($test)) {
            $test = $this->option('-t');
        }

        // identifiant du pid
        $pid = $this->option('--pid');
        if(empty($pid)) {
            $pid = getmypid();
        }
        $pid = str_replace('\n','',$pid);
        $this->pid = $pid;

        // mise à zero
        $reset = $this->option('--reset');
        if(empty($reset)) {
            $reset = $this->option('-r');
        }

        // force la remise à zero avant l'envoi
        $forcereset = $this->option('--forcereset');
        if(empty($forcereset)) {
            $forcereset = $this->option('-f');
        }

        // le message
        $message = jDao::get($this->dao_message);
        $message_infos = $message->get($idmessage);

        // message inconnu
        if(empty($message_infos->idmessage)) {
            $rep->addContent('L\'identifiant ne correspond pas a un message valide'.$this->n);
            return $rep;
        }

        // vérifier le no_send pour les tests
        if($GLOBALS['gJConfig']->debug_sendui['noSend']) {
            $this->no_send = true;    
        }

        // un dao utile
        $subscriber = jDao::get($this->dao_subscriber);

        // la table de process
        $process = jDao::get($this->dao_process);

        // utilitaires
        $utils = jClasses::getService('sendui~utils');
        
        // destinataires
        jClasses::inc($this->class_batch);

        // les destinataires trouvé via batch
        $batch = new Batch($idmessage);

        // si la table n'existe pas, on la crée
        if(!$batch->isTable()) {
            $batch->copyTable();    
        }
        
        $subscribers_list = $batch->getSubscribers(); 

        // compter le nb d'abonné
        $nb_subscribers = 0;
        foreach($subscribers_list as $s) {
            $nb_subscribers++;
        }

        // mise à zéro du champ d'envoi, vide process et change status
        if(!empty($reset) || !empty($forcereset)) {

            $this->setLog('Remise a zero du champ "sent" et des logs/process/compteur');

            // mise à zero du champ sent dans la table batch
            $batch->resetSent();

            // change le status de message à 1 et resetn le compteur
            $message->setStatus($idmessage,2);
            $message->resetCount($idmessage);

            // vide les enregistrements process du message
            $process->deleteLogs($idmessage);

            // ne pas continuer sauf si forcé
            if(empty($forcereset)) {
                $this->setLog('OK');
                exit;
            }

        }

        // on instancie swiftmailer
        require_once JELIX_APP_PATH.'/lib/swiftmailer/lib/swift_required.php';

        // smtp ou aws
        /*if($message_infos->sending_transport=='aws') {
            $transport = Swift_SmtpTransport::newInstance($GLOBALS['gJConfig']->mailerInfo['sSmtpHost'], 587)
                ->setUsername($GLOBALS['gJConfig']->mailerInfo['sSmtpUsername'])
                ->setPassword($GLOBALS['gJConfig']->mailerInfo['sSmtpPassword']);
        } else {
            $transport = Swift_SmtpTransport::newInstance($GLOBALS['gJConfig']->mailerInfo['sSmtpHost'], 587)
                ->setUsername($GLOBALS['gJConfig']->mailerInfo['sSmtpUsername'])
                ->setPassword($GLOBALS['gJConfig']->mailerInfo['sSmtpPassword']);
        }*/

        $transport = Swift_SmtpTransport::newInstance($GLOBALS['gJConfig']->mailerInfo['sSmtpHost'], 587)
            ->setUsername($GLOBALS['gJConfig']->mailerInfo['sSmtpUsername'])
            ->setPassword($GLOBALS['gJConfig']->mailerInfo['sSmtpPassword']);

        // objet
        $mailer = Swift_Mailer::newInstance($transport);
    
        // on précise le serveur de mail
        /*if(!empty($GLOBALS['gJConfig']->mailerInfo['sMailServer'])) {
            $mailer->setDomain($GLOBALS['gJConfig']->mailerInfo['sMailServer']);
        }*/

        // composition du message
        $message_compose = Swift_Message::newInstance();
        $message_compose->setReturnPath($message_infos->return_path); // adresse de retour des bounces

        // sujet
        $message_compose->setSubject($message_infos->subject);

        // expediteur
        $message_compose->setFrom(array($message_infos->from_email => $message_infos->from_name));

        // entêtes
        $headers = $message_compose->getHeaders();

        // reply-to
        if($message_infos->reply_to!='') {
            $headers->addTextHeader('Reply-to', $message_infos->reply_to);
        }

        //  entêtes antispam
        $headers->addTextHeader('X-Mailer', 'grafactory.net');
        $headers->addTextHeader('X-Complaints-To', 'abuse@grafactory.net');

        // replacement dans les messages
        jClasses::inc('sendui~template');

        // champs disponibles pour le remplacement
        $replace_array = array('email','lastname','firstname','token');
       
        function replaceArray($row,$replace_array) {
            foreach($replace_array as $k=>$v) {
                $tab[$v] = $row->$v;    
            }
            return $tab;
        }

        // envoyer un message de test seulement
        if(!empty($test)) {

            // doit contenir des mails
            $emails_tab = explode(',', $test);

            $emails = array();

            foreach($emails_tab as $e) {
                if(filter_var($e, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $e;
                }
            }

            if(empty($emails)) {
                $this->setLog('[FATAL] Il n\'y a aucun destinataire pour le message de test');
                exit;
            }

            $nb_emails_test = count($emails);

            if($nb_emails_test==0) {
                $this->setLog('[FATAL] Il n\'y a aucun email pour le message de test');
                exit;
            } else {
                $this->setLog('[TEST] Envoi du message ['.$idmessage.'] "'.$message_infos->subject.'" à '.$nb_emails_test.' adresse(s) de test');    
            }

            // message TODO
            //$headers->addTextHeader('List-Unsubscribe', 'http://www.grafactory.net/send-test');

            // contenu HTML ou simple TEXT
            if($message_infos->html_message!='') {
                $message_compose->setBody($message_infos->html_message, 'text/html');
            } else {
                $message_compose->setBody($message_infos->text_message, 'text/plain');
            }

            // contenu TEXT en plus du HTML
            if($message_infos->html_message!='' && $message_infos->text_message!='') {
                $message_compose->addPart($message_infos->text_message, 'text/plain');
            }

            // destinataire
            $message_compose->setTo($emails);

            if($this->no_send) {
                $success = true;
                $this->setLog('[NOTICE]] Pas d\'envoi, test seulement');    
            } else {
                $success = $mailer->send($message_compose);
            }

            if($success) {
                $this->setLog('[TEST] Message ['.$idmessage.'] "'.$message_infos->subject.'" envoyé');    
            } else {
                $this->setLog('[FATAL] Problème pendant l\'envoi de test du message ['.$idmessage.']');    
            }

            return $rep;

        }

        // les infos pause et lot sont dans la conf du message aussi (pause, batch)
        if($nb_subscribers==0) {
            $this->setLog('[NOTICE] Il n\'y a aucun abonné pour ce message. Avez-vous réinitialisé le champ "sent" (option -r)');
            exit;
        } else {
            $this->setLog('[START] Envoi du message ['.$idmessage.'] "'.$message_infos->subject.'" à '.$nb_subscribers.' abonnés');    
        }

        // valeur max
        $max = 0;

        // récuperer le compteur
        $max_record = $process->getMaxCounter($idmessage);
        $max = $max_record->max+1;

        // commencer la boucle

        // début 
        $time_start = microtime(true);

        // marquer le début et le statut en cours d'envoi
        $message->setStart($idmessage);
        $message->setStatus($idmessage,2);

        // marquer l'envoi à la liste

        // init compteurs
        $i = $max;
        $count_success = 0;

        foreach($subscribers_list as $s) {

            $email = $s->email;
            $email = strtolower($email);    
            
            // on verifie la validite syntaxique de l'adresse mail
            if (!$utils->isEmailSyntaxValid($email)) {
                if(empty($silent)) {
                    $this->setLog('Adresse '.$email.' ['.$s->idsubscriber.'] non valide');
                }
                // TODO : marquer l'email invalide dans la base ou dans un fichier de logs
                continue;
            }

            // si force limitation
            if (!empty($limit) && $i>= $limit) {
                break;
            }

            // la pause (deconnection/reconnection)
            if (($i % $message_infos->batch) == 0 && $i>1) {

                // on deconnecte avant la pause
                try {
                    $mailer->getTransport()->stop();
                    $this->setLog('[DISCONNECT] Déconnexion');
                } catch (Exception $e) {
                    if(empty($silent)) {
                        $this->setLog('[WARN] Déconnexion impossible, arrêt de l\'envoi');
                    }
                    exit;
                }
                
                if (empty($silent)) {
                    $this->setLog('[PAUSE] Pause de '.$message_infos->pause.' seconde(s) au niveau '.$i.' (CTRL+c pour couper le script)');
                }

                for($t=0;$t<$message_infos->pause;$t++) {
                    if(empty($silent)) {
                        echo ".";
                    }
                    sleep(1);
                }

                if(empty($silent)) {
                    echo "\n";
                }
                // on reconnecte apres la pause
                try {
                    $mailer->getTransport()->start();
                    if(empty($silent)) {
                        $this->setLog('[CONNECT] Connexion');
                    }
                } catch (Exception $e) {
                    if(empty($silent)) {
                        $this->setLog('[WARN] Connexion impossible, arrêt de l\'envoi');
                    }
                    exit;
                }

            }

            // on envoie effectivement le message (sauf si option nosend)
            $success = false;

            if (empty($nosend)) {

                // remplacement
                $r = new Template(replaceArray($s,$replace_array));

                //$headers->addTextHeader('List-Unsubscribe', $_SERVER['HTTP_HOST'].'/public/unsubscribe/?t='.$s->token);

                // contenu HTML ou simple TEXT
                if($message_infos->html_message!='') {
                    $message_compose->setBody($r->parse($message_infos->html_message), 'text/html');
                } else {
                    $message_compose->setBody($r->parse($message_infos->text_message), 'text/plain');
                }

                // contenu TEXT en plus du HTML
                if($message_infos->html_message!='' && $message_infos->text_message!='') {
                    $message_compose->addPart($r->parse($message_infos->text_message), 'text/plain');
                }

                // destinataire
                $message_compose->setTo($email);

                if($this->no_send) {
                    $success = true;
                    $this->setLog('[NOTICE]] Pas d\'envoi, test seulement');    
                } else {
                    $success = $mailer->send($message_compose);
                }

                // on comptabilise les succes
                if ($success) $count_success++;

            }

            // on met a jour le flag "envoye" dans la table batch et dans l'enregistrement subscriber le count dans message
            if (empty($noupdate) && ($success || $nosend)) {
                $batch->updateSent($s->idsubscriber);
                $subscriber->updateSent($s->idsubscriber);
                $message->updateCount($idmessage);
            }

            if (empty($silent)) {
                $this->setLog('[SEND] Envoi en cours à '.$email.' ['.$s->idsubscriber.']');
            }

            // on enregistre le process
            if ($success || $nosend) {
                $log = '[sendTo] '.$email.' [ID'.$s->idsubscriber.']';
                $record_process = jDao::createRecord($this->dao_process);
                $record_process->log = $log;
                $record_process->pid = $pid;
                $record_process->idmessage = $idmessage;
                $record_process->counter = $i;
                $process->insert($record_process);
            }

            $i++;

            // si le fichier stop_now_idmessage existe, on stoppe l'execution en sortant de la boucle
            if(file_exists(JELIX_APP_LOG_PATH.'process/stop_now_'.$idmessage)) {
                $status_message = 3;
                if(!unlink(JELIX_APP_LOG_PATH.'process/stop_now_'.$idmessage)) {
                    $this->setLog('[FATAL] Impossible de supprimer le fichier '.JELIX_APP_LOG_PATH.'process/stop_now_'.$idmessage);
                }
                break;
            }

        }

         // deconnexion
        $mailer->getTransport()->stop();

        // fin
        $time_end = microtime(true);

        // temps d'execution
        $time_exec = $time_end - $time_start;

        // stop ou fin ?
        if(!empty($status_message)) {
            $message->setStatus($idmessage,$status_message);
            $this->setLog('[STOP] Envoi stoppé après '.$utils->getTimeExec($time_start,$time_end).' ('.$count_success.'/'.$i.') !');
        } else {
            // marquer la fin et le status à 5
            $message->setEnd($idmessage);
            $message->setStatus($idmessage,5);
            $this->setLog('[END] Envoi terminé en '.$utils->getTimeExec($time_start,$time_end).' ('.$count_success.'/'.$i.') !');
        }
        
        return $rep;

    }

    // {{{ setLog()

    /**
     * Envoyer les logs dans le fichier ou sur la sortie standard
     *
     * @return      redirect
     */

    protected function setLog($msg)
    {

        $msg = '['.$this->pid.']['.$this->idmessage.'] '.$msg;

        // affiche
        if($this->verbose) {
            echo $msg.$this->n;
        }

        // logue dans un fichier
        if($GLOBALS['gJConfig']->debug_sendui['logSend']) {
            jLog::log($msg, $this->log_file);
        }    

    }

    // {{{ stop()

    /**
     * Stopper l'envoi
     *
     * @return      redirect
     */
    public function stop($pid)
    {

        if(empty($this->pid)) {
            $this->pid = $pid;    
        }

        $run = jClasses::getService('sendui~run');
        $run->stopProcess($this->pid);

        // on mets le champs status sur 3
        $message = jDao::get($this->dao_message);
        $message->setStatus($this->idmessage,3);

        // ici on log le pid et l'id du message
        $this->setLog('[STOP]  Arrêt demandé via console');
        
        return true;

    }

    // }}}

    // {{{ stop()

    /**
     * Action à executer quand sigterm ou sigint
     *
     * @return      exit
     */
    public function signalHandler($signal)
    {

        $sign = null;

        // type d'erreur
        switch($signal) {
            case SIGTERM:
                $sign = 'Caught SIGTERM';
            case SIGKILL:
                $sign = 'Caught SIGKILL';
            case SIGINT:
                $sign = 'Caught SIGINT';
        }

        // on mets le champs status sur 3 (suspendu)
        $message = jDao::get($this->dao_message);
        $message->setStatus($this->idmessage,3);

        // marquer dans le log
        $this->setLog('[STOP] Envoi stoppé par un signal '.$sign);

        exit;

    }

}
?>

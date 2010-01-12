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
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class sendingCtrl extends jControllerCmdLine {

    // message
    protected $dao_message = 'common~message';

    // abonnés
    protected $dao_subscriber = 'common~subscriber';
    protected $dao_subscriber_subscriber_list = 'common~subscriber_subscriber_list';

    // log
    protected $dao_process = 'common~process';

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
            '--reset' => false,
            '-r' => false,
            '--pid' => true,
            '-p' => true,
        ),
    );

    public $help = array(
        'index' => '
            -i --idmessage : identifiant du message
            -r --reset : remettre a zero le flag d\'envoi
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

        $n = "\n";

        $rep = $this->getResponse(); 

        $idmessage = $this->option('--idmessage');
        if(empty($idmessage)) {
            $idmessage = $this->option('-i');
        }

        // il faut l'identifiant du message
        if(empty($idmessage)) {
            $rep->addContent('Vous devez préciser l\'identifiant du message'.$n);
            return $rep;
        }

        // identifiant du pid
        $pid = $this->option('--pid');
        if(empty($idmessage)) {
            $pid = null;
        }

        // mise à zero
        $reset = $this->option('--reset');
        if(empty($reset)) {
            $reset = $this->option('-r');
        }

        // le message
        $message = jDao::get($this->dao_message);
        $message_infos = $message->get($idmessage);

        // la table de process
        $process = jDao::get($this->dao_process);

        // les destinataires trouvé via message_subscriber_list
        $subscriber = jDao::get($this->dao_subscriber);
        $subscribers_list = $subscriber->getSubscribers($idmessage,1); 

        // marqué envoyé ou autres actions
        $subscriber_subscriber_list = jDao::get($this->dao_subscriber_subscriber_list);

        // message inconnu
        if(empty($message_infos->idmessage)) {
            $rep->addContent('L\'identifiant ne correspond pas a un message valide'.$n);
            return $rep;
        }

        // mise à zéro du champ d'envoi
        if(!empty($reset)) {
            $rep->addContent('Remise a zero du champ "sent" '.$n);
            $subscriber_subscriber_list->resetSent($idmessage);
            exit;
        }

        // on instancie swiftmailer
        require_once JELIX_APP_PATH.'/lib/swiftmailer/lib/swift_required.php';

        // smtp
        $transport = Swift_SmtpTransport::newInstance($GLOBALS['gJConfig']->mailerInfo['sSmtpHost'], 587)
            ->setUsername($GLOBALS['gJConfig']->mailerInfo['sSmtpUsername'])
            ->setPassword($GLOBALS['gJConfig']->mailerInfo['sSmtpPassword'])
        ;

        // objet
        $mailer = Swift_Mailer::newInstance($transport);
    
        // on précise le serveur de mail
        /*if(!defined(SERVER_MAIL)) {
            $mailer->setDomain(SERVER_MAIL);
        }*/

        // composition du message
        $message = Swift_Message::newInstance();
        $message->setReturnPath($message_infos->return_path); // adresse de retour des bounces

        // sujet
        $message->setSubject($message_infos->subject);

        // expediteur
        $message->setFrom(array($message_infos->from_email => $message_infos->from_name));

        // entêtes
        $headers = $message->getHeaders();

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

            // remplacement avec valeur du client ?

            // message

            // envoi
            
        }

        // les infos pause et lot sont dans la conf du message aussi (pause, batch)

        // commencer la boucle

        // init compteurs
        $i = 1;
        $count_success = 0;

        foreach($subscribers_list as $s) {

            $email = $s->email;
            $email = strtolower($email);    
            
            // on verifie la validite syntaxique de l'adresse mail
            /*if (!$utils->isEmailSyntaxValid($email)) {
                   if(!$silent) {
                    echo '-> Adresse '.$email.' ['.$s->idsubscriber[ID_USERS].'] non valide'.$n;
                }
                // TODO : marquer l'email invalide dans la base ou dans un fichier de logs
                continue;
            }*/

            // si force limitation
            if (!empty($limit) && $i>= $limit) {
                break;
            }

            // la pause (deconnection/reconnection)
            if (($i % $message_infos->batch) == 0 && $i>0) {

                // on deconnecte avant la pause
                try {
                    $mailer->getTransport()->stop();
                    if(empty($silent)) {
                        echo "--> Deconnexion\n";
                    }
                } catch (Exception $e) {
                    if(empty($silent)) {
                        echo '--> Deconnexion impossible, arret de l\'envoi'.$n;
                    }
                    exit;
                }
                
                if (empty($silent)) {
                    echo '==============================> Pause de '.$message_infos->pause.' seconde(s) au niveau '.$i.' (CTRL+c pour couper le script)'.$n;
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
                        echo "--> Connexion\n";
                    }
                } catch (Exception $e) {
                    if(empty($silent)) {
                        echo '--> Connexion impossible, arret de l\'envoi'.$n;
                    }
                    exit;
                }

            }

            // on envoie effectivement le message (sauf si option nosend)
            $success = false;

            if (empty($nosend)) {

                // remplacement
                $r = new Template(replaceArray($s,$replace_array));

                $headers->addTextHeader('List-Unsubscribe', 'http://www.grafactory.net/'.$s->token);

                // contenu HTML ou simple TEXT
                if($message_infos->html_message!='') {
                    $message->setBody($r->parse($message_infos->html_message), 'text/html');
                } else {
                    $message->setBody($r->parse($message_infos->text_message), 'text/plain');
                }

                // contenu TEXT en plus du HTML
                if($message_infos->html_message!='' && $message_infos->text_message!='') {
                    $message->addPart($r->parse($message_infos->text_message), 'text/plain');
                }

                // destinataire
                $message->setTo($email);

                $success = $mailer->send($message);
                //$success = true;

                // on comptabilise les succes
                if ($success) $count_success++;

            }

            // on met a jour le flag "envoye" dans subscriber_subscriber_list
            if (empty($noupdate) && ($success || $nosend)) {
                $subscriber_subscriber_list->updateSent($s->idsubscriber,$s->idsubscriber_list);
            }

            if (empty($silent)) {
                echo '-> Envoi en cours a '.$email.' ['.$s->idsubscriber.']'.$n;
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

        }

         // deconnexion
        $mailer->getTransport()->stop();

        // fin
        /*$time_end = microtime(true);

        // temps d'execution
        $time_exec = $time_end - $time_start;
        
        // fin de l'envoi
        echo "=======> Envoi termine en ".$utils->timeExec($time_start,$time_end)." (".$count_success."/".$i.") !\n";*/

        // le dao de process
        //$process = jDao::get($this->dao_process);

        return $rep;

    }

}

?>

<?php
error_reporting(0);
/**
 * Handle bounce-back mails
 *
 * @package  BMH - BounceMailHandler
 * @author  Kevin : Wed Sep 06 11:18:05 PDT 2006
 *
 */

define('VERBOSE_QUIET',0);     // means no output at all
define('VERBOSE_SIMPLE',1);     // means only output simple report
define('VERBOSE_REPORT',2);     // means output a detail report
define('VERBOSE_DEBUG',3);      // means output detail report as well as debug info.

require_once _PATH_BMH.'bounce_rules.php';

class BounceMailHandler {

    /**
     * The host of pop3 mail server
     *
     * @var string
     */
    var $pop3_host='localhost';

    /**
     * The username of mailbox
     *
     * @var string
     */
    var $mailbox_username;

    /**
     * The password needed to access mailbox
     *
     * @var string
     */
    var $mailbox_password;

    /**
     * The last error msg
     *
     * @var string
     */
	var $error_msg ;

    /**
     * maxium limit bounces processed in one batch
     *
     * @var int
     */
    var $max_messages=30;

    /**
     * bounce action function name
     *    the function who actually handle the bounce mail, should take an array of parameters:
     * action body bounce_type charset date diag_code dsn_msg dsn_report email from md5header
     * remove rule_cat rule_no rule_type status_code subject
     *
     * @var string
     */
    var $action_function=null;

    /**
     * unmatched action function name
     *    the function who actually handle unmatched bounce mail, should take an array of parameters:
     * action body bounce_type charset date diag_code dsn_msg dsn_report email from md5header
     * remove rule_cat rule_no rule_type status_code subject
     *
     * @var string
     */
    var $unmatched_function=null;

    /**
     * log bounce function name
     *    the function who actually logs all bounce mail, should take an array of parameters:
     * action body bounce_type charset date diag_code dsn_msg dsn_report email from md5header
     * remove rule_cat rule_no rule_type status_code subject
     *
     * @var string
     */
    var $log_function=null;

    /**
     * Internal variable
     * The resource handler for the opened mailbox (POP3/IMAP/NNTP/etc.)
     *
     * @var object
     */
    var $_mailbox_link=false;

    /**
     * Test mode : didn't modify anything
     *
     * @var boolean
     */
	var $testmode = true ;

    /**
     * Purge the unknown messages or not
     *
     * @var boolean
     */
	var $purge_unprocessed = false ;

    /**
     * Purge the processed messages or not
     *
     * @var boolean
     */
	var $purge_processed = true ;

    /**
     * control the debug output, default is VERBOSE_SIMPLE
     *
     * @var int
     */
    var $verbose = VERBOSE_SIMPLE;

    /**
     * control the output of progress dots, default is true
     *
     * @var boolean
     */
    var $show_progress = true;

    /**
     * control the failed DSN rules output
     *
     * @var boolean
     */
	var $debug_dsn_rule = false ;

    /**
     * control the failed BODY rules output
     *
     * @var boolean
     */
	var $debug_body_rule = false ;

    /**
     * internal counter for log entries
     *
     * @var int
     */
	var $c_log = 0 ;

    /**
     * Initial the class
     *
     * @return BounceMail
     */
    function BounceMailHandler(){
    }

    /**
     * Output additional msg for debug
     *
     * @param string $msg,  if not given, output the last error msg
     * @param string $verbose_level,  the output level of this message
     * @param string $newline,  default is "\n"
     */
    function output($msg=false,$verbose_level=VERBOSE_SIMPLE,$newline="\n"){
        if ($this->verbose >= $verbose_level){
            if (empty($msg)){
                echo $this->error_msg.$newline;
            }
            else{
                echo $msg.$newline;
            }
        }
    }

    /**
     * Open a mail box via pop3 or IMAP
     *
     * @param string $host           The host address of the pop3 server, if not given , using the property $pop3_host
     * @param string $username       The username, if not given , using the property $mailbox_username
     * @param string $password       The password, if not given , using the property $mailbox_password
     * @return boolean, success or not
     */
    function openPop3($host=false,$username=false, $password=false, $port=143, $service=false, $service_option=false){
        if (!empty($host)){
            $this->pop3_host=$host;
        }
        if (!empty($username)){
            $this->mailbox_username=$username;
        }
        if (!empty($password)){
            $this->mailbox_password=$password;
        }

        #$port = '110/pop3/notls';
        $port = '143/imap/novalidate-cert';
        set_time_limit(6000);

        if (!$this->testmode) {
            $this->_mailbox_link =imap_open("{".$this->pop3_host.":".$port."}INBOX",$this->mailbox_username,$this->mailbox_password,CL_EXPUNGE);
        } else {
            $this->_mailbox_link =imap_open("{".$this->pop3_host.":".$port."}INBOX",$this->mailbox_username,$this->mailbox_password);
        }

        if (!$this->_mailbox_link){
            $this->error_msg="Cannot create POP3 connection to $this->pop3_host \nError MSG:".imap_last_error();
            $this->output();
            return false;
        }
        else{
            $this->output("Connect to $this->pop3_host successed");
            return true;
        }
    }

    /**
     * Open a mail box in local file system
     *
     * @param string $file_path           The local mbox file path
     * @return boolean, success or not
     */
    function openLocal($file_path){

        set_time_limit(6000);

        if (!$this->testmode) {
            $this->_mailbox_link =imap_open("$file_path",'','',CL_EXPUNGE);
        } else {
            $this->_mailbox_link =imap_open("$file_path",'','');
        }

        if (!$this->_mailbox_link){
            $this->error_msg="Cannot open the mailbox file to $file_path \nError MSG:".imap_last_error();
            $this->output();
            return false;
        }
        else{
            $this->output("Open $file_path successed");
            return true;
        }
    }

    /**
     * Process the messages in a mailbox
     *
     * @param string $max       The maxium limit bounces processed in one batch, if not given , using the property $max_messages
     * @return boolean, success or not
     */
    function processMailbox($max=false){
        if (empty($this->action_function)
            && empty($this->unmatched_function)
            && empty($this->log_bounces_function)) {
            $this->error_msg='At least one action needs to be defined!';
            $this->output();
            return false;
        }

        if (!empty($max)){
            $this->max_messages=$max;
        }

        // initialize counters
        $c_total=imap_num_msg($this->_mailbox_link);
        $c_fectched=$c_total;
        $c_processed=0;
        $c_unprocessed=0;
        $c_delete=0;
        $this->c_log=0;
        $this->output( "Total ".$c_total . " bounces in the mailbox ");
        // proccess maxium number of bounces
        if ($c_fectched > $this->max_messages) {
            $c_fectched = $this->max_messages;
            $this->output( "Processing first $c_fectched bounces ");
        }

        if ($this->testmode) {
            $this->output( "Running in test mode, not deleting messages from mailbox");
            $this->purge_processed = false;
            $this->purge_unprocessed = false;
        } 
        
        if ($this->purge_processed) {
            $this->output( "Processed messages will be deleted from mailbox");
        } else {
            $this->output( "Processed messages will NOT be deleted from mailbox");
        }
        
        if ($this->purge_unprocessed) {
            $this->output( "Unprocessed messages will be deleted from mailbox");
        } else {
            $this->output( "Unprocessed messages will NOT be deleted from mailbox");
        }
        
        for($x=1; $x <= $c_fectched; $x++) {
            $this->output( $x . ":",VERBOSE_REPORT,false);
            if ($this->show_progress) {
                if ($x == 1) {
                    $this->output(sprintf("\n% 6d: .",0),VERBOSE_SIMPLE,false);
                } else if ($x % 100 == 0) {
                    $this->output(sprintf("\n% 6d: ",$x),VERBOSE_SIMPLE,false);
                }

                if ($x % 10 == 0){
                    $this->output( '.',VERBOSE_SIMPLE,false);
                }
            }
            // process

            $processed = false;

            // fetch the mail one by one
                $header = imap_fetchheader($this->_mailbox_link,$x);
                // TRICKY : could be multi-line , if the new line is beginning with SPACE or HTAB
                if (preg_match ("/Content-Type:((?:[^\n]|\n[\t ])+)(?:\n[^\t ]|$)/is",$header,$match)){
                    if (preg_match("/multipart\/report/is",$match[1]) && preg_match("/report-type=[\"']?delivery-status[\"']?/is",$match[1])){
                        // standard DSN msg
                        $processed = $this->processBounce($x,'DSN',$header);
                    }
                    else{
                        // not a standard DSN msg
                        $this->output( "The No. $x is not a standard bounce mail",VERBOSE_REPORT);
                        if ($this->debug_body_rule){
                            $this->output( "  Content-Type : {$match[1]}",VERBOSE_DEBUG);
                        }
                        $processed = $this->processBounce($x,'BODY',$header);
                    }
                }
                else{
                    // didn't get the content-type header
                    $this->output( "The No. $x is not a well-formatted MIME mail, missing Content-Type",VERBOSE_REPORT);
                    if ($this->debug_body_rule){
                        $this->output( "  Headers : \n$header\n",VERBOSE_DEBUG);
                    }
                    $processed = $this->processBounce($x,'BODY',$header);
                }


            if ($processed) {
                // success processed
                $c_processed++;
                if ($this->purge_processed) {
                    // delete the bounce if not in test mode
                    imap_delete($this->_mailbox_link,$x);
                    $c_delete++;
                }
            }
            else {
                // not processed
                $c_unprocessed++;
                if ($this->purge_unprocessed) {
                    // delete this bounce if not in test mode and the flag BOUNCE_PURGE_UNPROCESSED is set
                    imap_delete($this->_mailbox_link,$x);
                    $c_delete++;
                }
            }
            flush();
        }
        $this->output( "\nClosing mailbox, and purging messages");
        imap_close($this->_mailbox_link);
        $this->output( "  Fetched $c_fectched bounces from the mailbox");
        $this->output( "    ".$this->c_log." bounces has been logged");
        $this->output( "    $c_processed bounces has been processed");
        $this->output( "    $c_unprocessed bounces has NOT been processed");
        $this->output( "    $c_delete bounces has been deleted from the mailbox");

        return true;
    }

    function processBounce($pos,$type,$header=null){
        if (!empty($header)) {
            $md5header = md5($header);
        } else {
            $md5header = null;
        }

        if ($type=='DSN') {

            // first part of DSN (Delivery Status Notification), human-readable explanation
            $dsn_msg=imap_fetchbody($this->_mailbox_link,$pos,"1");
            $dsn_msg_structure = imap_bodystruct($this->_mailbox_link,$pos,"1");

            if ($dsn_msg_structure->encoding == 3){
                $dsn_msg=base64_decode($dsn_msg);
            }
            // second part of DSN (Delivery Status Notification), delivery-status
            $dsn_report=imap_fetchbody($this->_mailbox_link,$pos,"2");

            // process bounces by rules
            $result=bmhDSNRules($dsn_msg,$dsn_report,$this->debug_dsn_rule);

        } elseif ($type=='BODY') {

            $structure = imap_fetchstructure($this->_mailbox_link,$pos);
            switch ($structure->type){
                case 0:
                    // Content-type = text
                case 2:
                    // Content-type = message
                    $body=imap_body($this->_mailbox_link,$pos);
                    if ($structure->encoding == 3){
                        $body=base64_decode($body);
                    }
                    $body=substr($body,0,1000);
                    $result=bmhBodyRules($body,$structure,$this->debug_body_rule);
                    break;
                case 1:
                    // Content-type = multipart
                    $body=imap_fetchbody($this->_mailbox_link,$pos,"1");
                    // TRICKY : detect encoding and decode
                    // only handle base64 right now
                    if ($structure->parts[0]->encoding == 3){
                        $body=base64_decode($body);
                    }
                    $body=substr($body,0,1000);
                    $result=bmhBodyRules($body,$structure,$this->debug_body_rule);
                    break;
                default:
                    // unsupport Content-type
                    $this->output( "The No. $pos is unsupport Content-Type:$structure->type",VERBOSE_REPORT);
                    return false;
            }

        } else {
            // internal error
            $this->error_msg='Internal Error: unknown type';
            return false;
        }

        $result['rule_type'] = $type;

        if (!empty($header)) {
            if (preg_match ("/Subject:((?:[^\n]|\n[\t ])+)(?:\n[^\t ]|$)/is",$header,$match)) {
                $result['subject'] = trim($match[1]);
            }
            if (preg_match ("/Date:[\t ]*(.*)/i",$header,$match)) {
                $result['date'] = trim($match[1]);
            }
            if (preg_match ("/From:((?:[^\n]|\n[\t ])+)(?:\n[^\t ]|$)/is",$header,$match)) {
                $addresses = imap_rfc822_parse_adrlist($match[1], '???');

                if (!empty($addresses) && is_array($addresses)) {
                    $result['from'] = $addresses[0]->mailbox.'@'.$addresses[0]->host;
                }
            }
        }

        // last chance for unmatched rules
        if ($result['rule_no']=='0000') {
             $result = bmhOtherRules($result);
        }

        $result['md5header']=$md5header;

        // log the result if wanted
        if (!empty($this->log_function) && function_exists($this->log_function)) {
            call_user_func($this->log_function, $result);
            $this->c_log++;
        }

        // call user function for unmatched rules
        if ($result['rule_no']=='0000'){
            if (!empty($this->unmatched_function) && function_exists($this->unmatched_function)) {
                return call_user_func($this->unmatched_function, $result);

            }
            return false;
        }

        if ($this->testmode){
                $this->output(print_r($result,true));
                return false;
        }

        // match a rule, take bounce action
        if (!empty($this->action_function) && function_exists($this->action_function)) {
            return call_user_func($this->action_function, $result);
        }

        return true;
    }
}
?>

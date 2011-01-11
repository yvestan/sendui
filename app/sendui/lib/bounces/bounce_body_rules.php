<?php

/**
 * Defined bounce parsing rules for non-standard DSN
 *
 * @param string $dsn_msg           human-readable explanation
 * @param string $dsn_report        delivery-status report
 * @param boolean $debug_mode        show debug info. or not
 * @return array    $result an array include the following fields: 'email', 'bounce_type','remove','rule_no','rule_cat'
 *                      if we could NOT detect the type of bounce, return rule_no = '0000'
 * @author  Kevin : Wed Sep 06 15:22:22 PDT 2006
 */
function bmhBodyRules($body,$structure,$debug_mode=false){
    // initial the result array
    $result=array(
        'email'=>''
        ,'bounce_type'=>false
        ,'remove'=>0
        ,'rule_cat'=>'unrecognized'
        ,'rule_no'=>'0000'
        ,'body' => wrapmsg($body)
    );

    /*if (isset($structure->parameters[0]->value) && $structure->parameters[0]->value=='CHARSET') {
	$result['charset']=$structure->parameters[0]->value;
    }*/

    // ======== rule =========
    if (false){
    }
    // rule : mailbox unknown;
    // sample :
    //
    //<fuxxxxxxxxxxx@hotmail.com.cn>:
    //Sorry, no mailbox here by that name. vpopmail (#5.1.1)
    //
    elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*no mailbox/i",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0157';
        $result['email']=$match[1];
    }
    // rule : mailbox unknown;
    // sample :
    //
    //tangjin00@eyou.com<br>
    //
    //local: Sorry, can't find user's mailbox. (#5.1.1)<br>
    //
    elseif (preg_match ("/(\S+@\S+\w)<br>.*\n?.*\n?.*can't find.*mailbox/i",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0164';
        $result['email']=$match[1];
    }
    // rule : mailbox unknown;
    // sample :
    //     ##########################################################
    //     #  This is an automated response from a mail delivery    #
    //     #  program.  Your message could not be delivered to      #
    //     #  the following address:                                #
    //     #                                                        #
    //     #      "|/usr/local/bin/mailfilt -u #dkms"               #
    //     #        (reason: Can't create output)                   #
    //     #        (expanded from: <dkms@cg03.u.washington.edu>)   #
    //     #                                                        #
    //
    elseif (preg_match ("/Can't create output.*\n?.*<(\S+@\S+\w)>/i",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0169';
        $result['email']=$match[1];
    }
    // rule : mailbox unknown;
    // sample :
    //无法将您的邮件投递至以下指定地址:
    //xxx@58866.com.cn : 投递失败, 帐号不存在.
    //
    //
    elseif (preg_match ("/(\S+@\S+\w).*=D5=CA=BA=C5=B2=BB=B4=E6=D4=DA/i",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0174';
        $result['email']=$match[1];
    }
    // rule : mailbox unknown;
    // sample :
    //
    //  xxxxx@hsmci.com
    //    Unrouteable address
    //
    elseif (preg_match ("/(\S+@\S+\w).*\n?.*Unrouteable address/i",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0179';
        $result['email']=$match[1];
    }
    // rule : mailbox unknow;
    // sample :
    //    Delivery to the following recipients failed.
    //
    //       squallhaur@hotmail.com
    elseif (preg_match ("/delivery[^\n\r]+failed\S*\s+(\S+@\S+\w)\s/is",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0013';
        $result['email']=$match[1];
    }

    //The following address(es) failed) YVES
    // rule : mailbox unknow;
    // sample :
    //    Delivery to the following recipients failed.
    //  
    //       squallhaur@hotmail.com
    elseif (preg_match ("/address\(es\) failed\S*\s+(\S+@\S+\w)\s/is",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0013';
        $result['email']=$match[1];
    }  

    // rule : mailbox unknow;
    // sample :
    //A message that you sent could not be delivered to one or more of its^M
    //recipients. This is a permanent error. The following address(es) failed:^M
    //^M
    //  kurama@atm.cyberec.com^M
    //    unknown local-part "kurama" in domain "atm.cyberec.com"^M
    //
    elseif (preg_match ("/(\S+@\S+\w).*\n?.*unknown local-part/i",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0232';
        $result['email']=$match[1];
    }
    // rule : mailbox unknow;
    // sample :
    //<cmzandrew@pacific.net.sg>:^M
    //203.120.90.11 does not like recipient.^M
    //Remote host said: 550 Invalid recipient: <cmzandrew@pacific.net.sg>^M
    //
    elseif (preg_match ("/Invalid.*(?:alias|account|recipient|address|email|mailbox|user).*<(\S+@\S+\w)>/i",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0233';
        $result['email']=$match[1];
    }
    // rule : mailbox unknow;
    // sample :
    //Sent >>> RCPT TO: <gerber@southepi.com>^M
    //Received <<< 550 gerber@southepi.com... No such user^M
    //^M
    //Could not deliver mail to this user.^M
    //gerber@southepi.com^M
    //*****************     End of message     ***************^M
    elseif (preg_match ("/\s(\S+@\S+\w).*No such.*(?:alias|account|recipient|address|email|mailbox|user)>/i",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0234';
        $result['email']=$match[1];
    }
    // rule : mailbox unknow;
    // sample :
    //<mark@dsff.com>:^M
    //This address no longer accepts mail.
    elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*(?:alias|account|recipient|address|email|mailbox|user).*no.*accept.*mail>/i",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0235';
        $result['email']=$match[1];
    }
    // rule : mailbox unknow;
    // sample :
    //Remote-MTA: dns; dpmailfw08sm.doteasy.com
    //Diagnostic-Code: smtp; 550 Gateway: 550 <lorcca@best-technologies.com> No such user here
    //
   /* elseif (preg_match ("/No such user here.*(?:alias|account|recipient|address|email|mailbox|user).*<(\S+@\S+\w)>/i",$body,$match)){
        $result['rule_cat']='unknown';
        $result['rule_no']='0233';
        $result['email']=$match[1];
    }*/
    // rule : full
    // sample :
    //
    //<georgie32@chek.com>:
    //This account is over quota and unable to receive mail.
    //
    // sample 2:
    //<kai79@net-yan.com>:
    //Warning: undefined mail delivery mode: normal (ignored).
    //The users mailfolder is over the allowed quota (size). (#5.2.2)
    //
    elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*\n?.*over.*quota/i",$body,$match)){
        $result['rule_cat']='full';
        $result['rule_no']='0182';
        $result['email']=$match[1];
    }
    // rule : mailbox full;
    // sample:
    //   ----- Transcript of session follows -----
    //mail.local: /var/mail/2b/10/kellen.lee: Disc quota exceeded
    //554 <kellen.lee@msa.hinet.net>... Service unavailable
    elseif (preg_match ("/quota exceeded.*\n?.*<(\S+@\S+\w)>/i",$body,$match)){
        $result['rule_cat']='full';
        $result['rule_no']='0126';
        $result['email']=$match[1];
    }
    // rule : mailbox full;
    // sample:
    //Hi. This is the qmail-send program at 263.sina.com.
    //
    //
    //<leslie98@263.sina.com>:
    // - User disk quota exceeded. (#4.3.0)
    //
    elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*quota exceeded/i",$body,$match)){
        $result['rule_cat']='full';
        $result['rule_no']='0158';
        $result['email']=$match[1];
    }
    // rule : mailbox full;
    // sample:
    //  yoko88@seed.net.tw
    //    mailbox is full (MTA-imposed quota exceeded while writing to file /mbx201/mbx011/A100/09/35/A1000935772/mail/.inbox):
    //
    elseif (preg_match ("/\s(\S+@\S+\w)\s.*\n?.*mailbox.*full/i",$body,$match)){
        $result['rule_cat']='full';
        $result['rule_no']='0166';
        $result['email']=$match[1];
    }
    // rule : mailbox full;
    // sample:
    //    The message to doingwhat@tom.com is bounced because : Quota exceed the hard limit
    //
    elseif (preg_match ("/The message to (\S+@\S+\w)\s.*bounce.*Quota exceed/i",$body,$match)){
        $result['rule_cat']='full';
        $result['rule_no']='0168';
        $result['email']=$match[1];
    }
    // rule : inactive
    // sample :
    //xxxxxxxxx46@eyou.com<br>
    //
    //553 user is inactive (eyou mta)
    //
    elseif (preg_match ("/(\S+@\S+\w)<br>.*\n?.*\n?.*user is inactive/i",$body,$match)){
        $result['rule_cat']='inactive';
        $result['rule_no']='0171';
        $result['email']=$match[1];
    }
    // rule : inactive
    // sample :
    //xxxxxn@mail2000.com.tw [Inactive account]
    //
    elseif (preg_match ("/(\S+@\S+\w).*inactive account/i",$body,$match)){
        $result['rule_cat']='inactive';
        $result['rule_no']='0181';
        $result['email']=$match[1];
    }
    // rule : internal_error
    // sample :
    //<ycxxxxxxxxxxxxxx@jstel.net>:
    //Unable to switch to /var/vpopmail/domains/jstel.net: input/output error. (#4.3.0)
    //
    elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*input\/output error/i",$body,$match)){
        $result['rule_cat']='internal_error';
        $result['rule_no']='0172';
        $result['bounce_type']='hard';
        $result['remove']=1;
        $result['email']=$match[1];
    }
    // rule : internal_error
    // sample :
    //
    //<casinoinstitude@fromc.com>:
    //can not open new email file errno=13 file=/home/vpopmail/domains/fromc.com/0/casinoinstitude/Maildir/tmp/1155254417.28358.mx05,S=212350
    //
    elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*can not open new email file/i",$body,$match)){
        $result['rule_cat']='internal_error';
        $result['rule_no']='0173';
        $result['bounce_type']='hard';
        $result['remove']=1;
        $result['email']=$match[1];
    }
    // rule : defer
    // sample:
    //<tcsslai@yahoo.com.hk>:
    //64.156.215.8 failed after I sent the message.
    //Remote host said: 451 mta283.mail.scd.yahoo.com Resources temporarily unavailable. Please try again later [#4.16.5].
    //
    elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*\n?.*Resources temporarily unavailable/i",$body,$match)){
        $result['rule_cat']='defer';
        $result['rule_no']='0163';
        $result['email']=$match[1];
    }
    // rule : autoreply
    // sample:
    //AutoReply message from fxxxxxxxxg@seed.net.tw
    //
    elseif (preg_match ("/^AutoReply message from (\S+@\S+\w)/i",$body,$match)){
        $result['rule_cat']='autoreply';
        $result['rule_no']='0167';
        $result['email']=$match[1];
    }
    // rule : western chars only
    // sample:
    //<rob@realmsolutions.com>:
    //The user does not accept email in non-Western (non-Latin) character sets.
    elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*does not accept[^\r\n]*non-Western/i",$body,$match)){
        $result['rule_cat']='latin_only';
        $result['rule_no']='0043';
        $result['email']=$match[1];
    }
    elseif (preg_match('/Hi\. This is the/', $body) && preg_match("/<(\S+@\S+\w)>:/",$body,$match)) {
        // QMAIL
        $result['email']=$match[1];
        $result['rule_cat']='qmail_bounce';
        $result['rule_no']='0237';

        // rule : mailbox unknown;
        // sample:
        //
        // Hi. This is the qmail-send program at a.mx.domainoo.fr.
        // I'm afraid I wasn't able to deliver your message to the following addresses.
        // This is a permanent error; I've given up. Sorry it didn't work out.
        //
        // <issauratl@hec.fr>:
        // 193.51.16.153 does not like recipient.
        // Remote host said: 550 5.1.1 <issauratl@hec.fr>: Recipient address rejected: User unknown in relay recipient table
        // Giving up on 193.51.16.153.
        if (preg_match ("/(?:User|Recipient) unknown/i",$body)){
            $result['rule_cat']='unknown';
            $result['rule_no']='0238';
        } else if (preg_match('/mailbox is full/i', $body)) {
            $result['rule_cat']='full';
            $result['rule_no']='0239';
        } else if (preg_match('/exceed.*quotas?/i', $body)) {
            $result['rule_cat']='full';
            $result['rule_no']='0240';
        } else if (preg_match('/user does not exist/i', $body)) {
            $result['rule_cat']='unknown';
            $result['rule_no']='0241';
        }
    }


    global $rule_categories;
    if ($result['rule_no']=='0000'){
        if ($debug_mode){
            echo "Body :\n$body\n";
            echo "\n";
        }
    }
    else{
        if ($result['bounce_type'] ===false){
            $result['bounce_type']=$rule_categories[$result['rule_cat']]['bounce_type'];
            $result['remove']=$rule_categories[$result['rule_cat']]['remove'];
        }
    }
    return $result;

}
?>

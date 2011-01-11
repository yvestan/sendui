<?php

function wrapmsg($txt)
{
    return $txt; //"\n>>>>>>>>>>>>\n".$txt."<<<<<<<<<<<<\n\n";
}

/**************
* next rule number : 0255
* default category:
    unrecognized : rule no. : 0000
*/
global $rule_categories;
$rule_categories=array(
    'unrecognized'=>    array('remove'=>0,'bounce_type'=>false,)
    ,'delayed'=>        array('remove'=>0,'bounce_type'=>'temporary')
    ,'defer'=>          array('remove'=>0,'bounce_type'=>'soft')
    ,'unknown'=>        array('remove'=>1,'bounce_type'=>'hard')
    ,'inactive'=>       array('remove'=>1,'bounce_type'=>'hard')
    ,'user_reject'=>    array('remove'=>1,'bounce_type'=>'hard')
    ,'oversize'=>       array('remove'=>0,'bounce_type'=>'soft')
    ,'full'=>           array('remove'=>0,'bounce_type'=>'soft')
    ,'dns_unknown'=>    array('remove'=>1,'bounce_type'=>'hard')
    ,'dns_loop'=>       array('remove'=>1,'bounce_type'=>'hard')
    ,'content_reject'=> array('remove'=>0,'bounce_type'=>'soft')
    ,'command_reject'=> array('remove'=>1,'bounce_type'=>'hard')
    ,'antispam'=>       array('remove'=>0,'bounce_type'=>'blocked')
    ,'concurrent'=>     array('remove'=>0,'bounce_type'=>'soft')
    ,'internal_error'=> array('remove'=>0,'bounce_type'=>'temporary')
    ,'warning'=>        array('remove'=>0,'bounce_type'=>'soft')
    ,'latin_only'=>     array('remove'=>0,'bounce_type'=>'soft')
    ,'autoreply'=>      array('remove'=>0,'bounce_type'=>'autoreply')
    ,'other'=>          array('remove'=>1,'bounce_type'=>'generic')
    ,'dsn_failed'=>     array('remove'=>0,'bounce_type'=>'soft')
    ,'qmail_bounce'=>   array('remove'=>0,'bounce_type'=>'soft')
);

require_once _PATH_BMH.'bounce_body_rules.php';
require_once _PATH_BMH.'bounce_dsn_rules.php';
require_once _PATH_BMH.'bounce_other_rules.php';
?>

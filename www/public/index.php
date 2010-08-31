<?php

/**
* @package   sendui
* @subpackage 
* @author    yourname
* @copyright 2008 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

if($_SERVER['SERVER_NAME']=='sendui') {
    if (!empty($_SERVER['SPECIAL_ID'])) {
        $pathApp = '/home/yvestan/perso/sites/sendui/sendui/';
    } else {
        $pathApp = '/Users/yves/Sites/sendui/web/';
    }
} else {
    $pathApp = '/home/grafactory/';
}

require ($pathApp.'libs/jelix/sendui/application.init.php');
require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

$config_file = 'public/config.ini.php';

$jelix = new jCoordinator($config_file);
$jelix->process(new jClassicRequest());

?>

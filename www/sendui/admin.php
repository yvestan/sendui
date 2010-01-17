<?php
/**
* @package   sendui
* @subpackage 
* @author    Yves Tannier [grafactory.net]
* @copyright 2009 Yves Tannier
* @link      http://www.grafactory.net/sendui
* @license   http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
*/

if($_SERVER['SERVER_NAME']=='sendui') {
    $pathApp = '/Users/yves/Sites/sendui/web/';
} else {
    $pathApp = '/home/grafactory/';
}

require ($pathApp.'libs/jelix/sendui/application.init.php');
require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

$config_file = 'admin/config.ini.php';

$jelix = new jCoordinator($config_file);
$jelix->process(new jClassicRequest());

?>

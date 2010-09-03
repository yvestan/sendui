<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 *
 * Init de l'application en CLI
 *
 * @package   sendui
 * @subpackage 
 * @author    Yves Tannier [grafactory.net]
 * @copyright 2009 Yves Tannier
 * @link      http://www.grafactory.net/sendui
 * @license   http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
*/

define ('JELIX_APP_PATH', dirname (__FILE__).DIRECTORY_SEPARATOR); // don't change

require (JELIX_APP_PATH.'/../lib/jelix/init.php');

define ('JELIX_APP_TEMP_PATH',    realpath(JELIX_APP_PATH.'../temp/sendui-cli/').'/');
define ('JELIX_APP_VAR_PATH',     JELIX_APP_PATH.'var/');
define ('JELIX_APP_LOG_PATH',     JELIX_APP_PATH.'var/log/');
define ('JELIX_APP_CONFIG_PATH',  JELIX_APP_PATH.'var/config/');
define ('JELIX_APP_WWW_PATH',     JELIX_APP_PATH.'www/');
define ('JELIX_APP_CMD_PATH',     JELIX_APP_PATH.'scripts/');


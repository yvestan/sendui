<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 *
 * Init de l'application en jelix
 *
 * @package   sendui
 * @subpackage 
 * @author    Yves Tannier [grafactory.net]
 * @copyright 2009 Yves Tannier
 * @link      http://www.grafactory.net/sendui
 * @license   http://www.grafactory.net/sendui/licence MIT Licence
*/


define ('JELIX_APP_PATH', dirname (__FILE__).DIRECTORY_SEPARATOR); // don't change

require (JELIX_APP_PATH.'/../lib/jelix/init.php');

define ('JELIX_APP_VAR_PATH',     JELIX_APP_PATH.'var/');
define ('JELIX_APP_LOG_PATH',     JELIX_APP_PATH.'var/log/');
define ('JELIX_APP_CONFIG_PATH',  JELIX_APP_PATH.'var/config/');
define ('JELIX_APP_WWW_PATH',     JELIX_APP_PATH.'www/');
define ('JELIX_APP_CMD_PATH',     JELIX_APP_PATH.'scripts/');

// the temp path for jelix-scripts
define ('JELIX_APP_TEMP_PATH',    realpath(JELIX_APP_PATH.'../temp/sendui-jelix-scripts/').'/');

// the temp path for cli scripts of the application
define ('JELIX_APP_TEMP_CLI_PATH',    realpath(JELIX_APP_PATH.'../temp/sendui-cli/').'/');

// the temp path for the web scripts of the application (the same value as JELIX_APP_TEMP_PATH in application.init.php)
define ('JELIX_APP_REAL_TEMP_PATH',    realpath(JELIX_APP_PATH.'../temp/sendui/').'/');

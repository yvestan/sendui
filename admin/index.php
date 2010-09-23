<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 *
 * Point d'entrée de l'administration
 *
 * @package   sendui
 * @subpackage 
 * @author    Yves Tannier [grafactory.net]
 * @copyright 2009 Yves Tannier
 * @link      http://www.grafactory.net/sendui
 * @license   http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
*/

require dirname(__FILE__).'/../app/sendui/application.init.php';
require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

$config_file = 'sendui/config.ini.php';

$jelix = new jCoordinator($config_file);
$jelix->process(new jClassicRequest());

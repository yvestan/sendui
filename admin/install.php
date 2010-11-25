<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 *
 * Test de l'installation
 *
 * @package   sendui
 * @subpackage 
 * @author    Yves Tannier [grafactory.net]
 * @copyright 2009 Yves Tannier
 * @link      http://www.grafactory.net/sendui
 * @license   http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
*/

// si lancé en cli, on teste la version de PHP et la présence de pctnl_signal
function isCli() {
     if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
          return true;
     } else {
          return false;
     }
}

$required_extensions = array(
    'simplexml',
    'dom',
    'pcre',
    'session',
    'spl',
    'tokenizer', 
);

$requirements = array(
    'magic_quotes_gpc' => 0,
    'magic_quotes_runtime' => 0,
    'session_auto_start' => 0,
    'safe_mode' => 0,
);

$recommended = array(
    'register_globals' => 0,
    'asp_tags' => 0,
    'short_open_tag' => 0,
);

 
if(isCli()) {

    $rtnl = "\n";
    $txt[] = '===> Test de votre environnement'.$rtnl;

    if (version_compare(PHP_VERSION, '5.2.0', '<')) {
        $txt[] = '[FATAL] Vous devez disposer de PHP 5.2 minimum, votre version détectée est '.phpversion();
    } else {
        $txt[] = '[OK] Vous utilisez '.PHP_VERSION.' (5.2 est le minimum requis)';
    }

    $txt[] = '';

    // test pcntl
    $ex = 'PCNTL';
    if(!function_exists('pcntl_signal')) {
        $txt[] = '[FATAL] L\'extension '.$ex.' ne semble pas chargée';
    } else {
        $txt[] = '[OK] L\'extension '.$ex.' est correctement chargée';
    }

    // test extension
    foreach($required_extensions as $ex) {
        if(!extension_loaded($ex)) {
            $txt[] = '[FATAL] L\'extension '.$ex.' ne semble pas chargée';
        } else {
            $txt[] = '[OK] L\'extension '.$ex.' est correctement chargée';
        }
    }

    $txt[] = '';

    // test config PHP
    foreach($requirements as $k=>$v) {
        if($v!=ini_get($k)) { 
            $txt[] = '[FATAL] '.$k.' doit être à '.$v;
        } else {
            $txt[] = '[OK] '.$k.' est bien à '.$v;
        }
    }

    // message magic_quote
    if(ini_get('magic_quotes_gpc')==1 || ini_get('magic_quotes_runtime')) {
        $txt[] = $rtnl.'[NOTE] Si vous ne pouvez pas changer la valeur de magic_quotes_gpc et/ou de magic_quotes_runtime, vous pouvez activer le plugin magicquotes livré avec jelix'.$rtnl;
    }

    // message safe mode
    if(ini_get('safe_mode')==1) {
        $txt[] = $rtnl.'[NOTE] Le système n\'a pas été testé avec le safe_mode activé mais il est possible que ça fonctionne ?!'.$rtnl;    
    }

    $txt[] = '';

    foreach($recommended as $k=>$v) {
        if($v!=ini_get($k)) { 
            $txt[] = '[OPTIONNEL] '.$k.' pourrait être à '.$v;
        } else {
            $txt[] = '[OK] '.$k.' est bien à '.$v;
        }
    }

    $txt[] = '';

    echo join($rtnl, $txt);

} else {

    require dirname(__FILE__).'/../app/sendui/application.init.php';

    require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');
    $config_file = 'sendui/config.ini.php';

    function titleUi($title) {
        return '<div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all"><h3>'.$title.'</h3></div>';
    }

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr_FR" lang="fr_FR">
    <head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type"/>
    <title>Test de votre installation</title>
    <link type="text/css" href="/admin/css/reset.css" rel="stylesheet" />
    <link type="text/css" href="/admin/css/style.css" rel="stylesheet" />
    <link type="text/css" href="/admin/css/themes/hot-sneaks/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="/admin/js/jquery-1.3.2.min.js" ></script>
    <script type="text/javascript" src="/admin/js/jquery-ui-1.7.2.custom.min.js" ></script>
    <script type="text/javascript" src="/admin/js/button.js" ></script>
    <script type="text/javascript" src="/admin/js/function.js" ></script>

    <link rel="icon" type="image/png" href="/admin/favicon.ico" /></head><body >
    <div id="global">

        <div id="header" class="ui-widget ui-widget-content ui-corner-all">

            <h1 id="logo">
                <a href="/admin/sendui/">grafactory.net</a>
            </h1>

            <div class="spacer">&nbsp;</div>

        </div>

        <div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
            <h2 class="main cog">Test de votre configuration</h2>

    <div class="sendui-standard-content">
    <?php

    // test de la version de PHP
    $html[] = titleUi('Version de PHP et de PHP-CLI');
    $html[] = '<div class="sendui-simple-content">';

    if (version_compare(PHP_VERSION, '5.2.0', '<')) {
        $html[] = '<p class="cross">Vous devez disposer de PHP 5.2 minimum, votre version détectée est <strong>'.phpversion().'</strong><p>';
    } else {
        $html[] = '<p class="tick">Vous utilisez '.PHP_VERSION.' (5.2 est le minimum requis)</p>';
    }

    $html[] = '<p class="lightbulb_off">Il vous faut aussi installer <strong>la version ligne de commande de PHP</strong> : PHP-CLI si elle ne l\'est pas encore.</p>';

    $html[] = '</div>';

    // test de apache rewrite
    $html[] = titleUi('Test de la réécriture d\'URL de Apache');
    $html[] = '<div class="sendui-simple-content">';

    $html[] = '<p class="lightbulb_off">Ce test n\'est pas fiable à 100% ! Particulièrement si... ce n\'est pas un serveur Apache</p>';

    if (in_array("mod_rewrite", apache_get_modules())) {
        $html[] = '<p class="tick">Le module mod_rewrite est correctement chargé</p>';
    } else {
        $html[] = '<p class="tick">Le module mod_rewrite ne semble pas chargé</p>';
    }

    $html[] = '</div>';

    // version de PHP sup ou égale à 5.2
    $html[] = titleUi('Extensions de PHP nécessaires');
    $html[] = '<div class="sendui-simple-content">';

    $required_extensions = array(
        'simplexml',
        'dom',
        'pcre',
        'session',
        'spl',
        'tokenizer', 
    );

    $html[] = '<ul>';
    foreach($required_extensions as $ex) {
        if(!extension_loaded($ex)) {
            $html[] = '<li class="cross">L\'extension '.$ex.' ne semble pas chargée</li>';
        } else {
            $html[] = '<li class="tick">L\'extension '.$ex.' est correctement chargée</li>';
        }
    }
    $html[] = '</ul>';


    $html[] = '<h3>Extension PCNTL</h3>';

    // test pcntl
    $ex = 'PCNTL';
    if(!function_exists('pcntl_signal')) {
        $html[] = '<p class="cross">L\'extension '.$ex.' ne semble pas chargée <strong>MAIS</strong> elle est peut-être chargée sur la version de PHP en ligne de commande (c\'est le cas sur Debian et Ubuntu) et c\'est suffisant ! <strong>Vous pouvez tester en lancant ce script en ligne de commande <code>php install.php</code></strong>.</p>';
    } else {
        $html[] = '<p class="tick">L\'extension '.$ex.' est correctement chargée</p>';
    }

    $html[] = '</div>';

    // test des répertoire
    $html[] = titleUi('Répertoire accessible en écriture');
    $html[] = '<div class="sendui-simple-content">';

    // test si le répertoire temp exist et est writable
    /*if(JELIX_APP_TEMP_PATH=='/' || !is_writable(JELIX_APP_TEMP_PATH)) {
        $html[] = '<p class="cross">l\'un des répertoires suivants n\'existe pas ou n\'est pas accessible en écriture par PHP</p>';
    } else {
        $html[] = '<p class="tick">Tous les répertoires suivants sont bien accessible en écriture</p>';
    }*/

    $directory_write = array(
        'app/temp',
        'app/temp/sendui',
        'app/temp/sendui-cli',
        'app/sendui/var/config',
        'app/sendui/var/config/cmdline',
        'app/sendui/var/config/sendui',
    ); 

    $html[] = '<ul>';
    foreach($directory_write as $k=>$v) {
        if(!is_writable('../'.$v)) {
            $html[] = '<li class="cross">'.$v.' doit être accessible en écriture</span>';
        } else {
            $html[] = '<li class="tick">'.$v.' est bien accessible en écriture</span>';
        }
    }
    $html[] = '</ul>';


    $html[] = '</div>';

    // test version de PHP
    $html[] = titleUi('Configuration de PHP');
    $html[] = '<div class="sendui-simple-content">';

    $html[] = '<ul>';
    foreach($requirements as $k=>$v) {
        if($v!=ini_get($k)) { 
            $html[] = '<li class="cross">'.$k.' doit être à '.$v.'</span>';
        } else {
            $html[] = '<li class="tick">'.$k.' est bien à '.$v.'</span>';
        }
    }
    $html[] = '</ul>';

    // message magic_quote
    if(ini_get('magic_quotes_gpc')==1 || ini_get('magic_quotes_runtime')) {
        $html[] = '<p class="lightbulb_off">Si vous ne pouvez pas changer la valeur de magic_quotes_gpc et/ou de magic_quotes_runtime, 
                    vous pouvez activer <a href="http://jelix.org/articles/fr/manuel-1.1/plugins/coord">le plugin magicquotes livré avec jelix</a></p>';
    }

    // message safe mode
    if(ini_get('safe_mode')==1) {
        $html[] = '<p class="lightbulb_off">Le système n\'a pas été testé avec le safe_mode activé mais il est possible que ça fonctionne ?!</p>';    
    }

    $html[] = '</div>';

    // test version de PHP
    $html[] = titleUi('Valeurs recommandées pour un fonctionnement optimal <strong>(optionnel)</strong>');
    $html[] = '<div class="sendui-simple-content">';
        
    $html[] = '<ul>';
    foreach($recommended as $k=>$v) {
        if($v!=ini_get($k)) { 
            $html[] = '<li class="cross">'.$k.' pourrait être à '.$v.'</span>';
        } else {
            $html[] = '<li class="tick">'.$k.' est bien à '.$v.'</span>';
        }
    }
    $html[] = '</ul>';

    $html[] = '</div>';

    echo join("\n", $html);
    ?>
        <div class="spacer">&nbsp;</div>

        <p><a href="/admin/sendui/install/" class="forms-submit fg-button ui-state-default ui-corner-all">Passez à l'étape suivante si vous avez bien configuré votre environnement</a></p>

    </div>

            <div class="spacer">&nbsp;</div>
        </div>

        <div id="footer" class="ui-widget ui-widget-content ui-corner-all">
            <ul>

                <li style="float:right;">Yves Tannier [<a href="http://www.grafactory.net">grafactory.net</a>]</li>
            </ul>
            <div class="spacer"></div>
        </div>

    </div>
    </body></html>
<?php
} // fin test cli
?>

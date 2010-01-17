<?php
 
/**
    * cfunction plugin :  init a yui editor environnement
    *    
    * @param jTplCompiler $compiler 
    */
function jtpl_cfunction_html_yuieditor($compiler, $params=array()) {
 
    // on génère du code php qui sera intégré dans le template compilé
    $codesource = '$rep = $GLOBALS[\'gJCoord\']->response;
        if($rep!=null) {
            $rep->addCSSLink(\'/sendui/js/yuieditor/assets/skins/sam/skin.css\');
            $rep->addJSLink(\'/sendui/js/yuieditor/yahoo-dom-event/yahoo-dom-event.js\');
            $rep->addJSLink(\'/sendui/js/yuieditor/element/element-min.js\');
            $rep->addJSLink(\'/sendui/js/yuieditor/container/container_core-min.js\');
            $rep->addJSLink(\'/sendui/js/yuieditor/menu/menu-min.js\');
            $rep->addJSLink(\'/sendui/js/yuieditor/button/button-min.js\');
            $rep->addJSLink(\'/sendui/js/yuieditor/editor/editor-min.js\');
            $rep->addJSLink(\'/sendui/js/yuieditor/yuieditor.js\');
            $rep->bodyTagAttributes = array (\'class\'=>\'yui-skin-sam\');
        } ';

    $compiler->addMetaContent($codesource);
}
?>

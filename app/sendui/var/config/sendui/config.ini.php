;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=sendui
startAction="default:index"

[coordplugins]
auth=auth.coord.ini.php

[responses]
html=senduiHtmlResponse
simple=simpleHtmlResponse
install=installHtmlResponse

[urlengine]
basePath=""

notfoundAct="sendui~error:notfound"

checkTrustedModules=on
trustedModules=sendui,common

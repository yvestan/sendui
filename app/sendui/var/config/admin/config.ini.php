;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=master_admin
startAction="default:index"

modulesPath="lib:jelix-admin-modules/,lib:jelix-modules/,app:modules/"

[coordplugins]
;name = file_ini_name or 1

auth="admin/auth.coord.ini.php"
jacl2="admin/jacl2.coord.ini.php"

[responses]
html=adminHtmlResponse
htmlauth=adminLoginHtmlResponse

[acl2]
driver=db
[simple_urlengine_entrypoints]
admin="jacl2db~*@classic, jauth~*@classic, jacl2db_admin~*@classic, jauthdb_admin~*@classic, master_admin~*@classic"

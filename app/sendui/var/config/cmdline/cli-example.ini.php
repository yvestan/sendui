;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule = "sendui"
startAction = "sending:index"

; sendui smtp configuration
[mailerInfo]
sSmtpUsername="smtp_username"
sSmtpPassword="smtp_password"
sSmtpHost="smtp.example.org"

; ne pas envoyer de mail
[debug_sendui]
noSend=false

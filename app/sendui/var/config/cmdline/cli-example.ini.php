;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule = "sendui"
startAction = "sending:index"

; sendui smtp configuration
[mailerInfo]
sSmtpUsername="smtp_username"
sSmtpPassword="smtp_password"
sSmtpHost="smtp.example.org"

; outils de debug
[debug_sendui]
; ne pas envoyer de mail
noSend=false
; loguer tous les envois
logSend=false

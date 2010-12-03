<?php

// inscription/désinscription

// URL de l'application sendUI
$url_sendui = 'http://domain.tld/public/subscribe';

// identifiant de la liste
$list_sendui = 'public_token_key_list';

// liste ou liste par défaut
if(!empty($_POST['sendui_action']) 
    && ($_POST['sendui_action']=='subscribe' || $_POST['sendui_action'] == 'unsubscribe')) {

    $sendui_action = $_POST['sendui_action'];

    // la classe rest
    include_once 'sendui/sendui.php';

    if(!empty($_POST['list'])) {
        $params['list'] = filter_var($_POST['list'],FILTER_SANITIZE_STRING);
    } else {
        $params['list'] = $list_sendui;
    }
    // email
    if(!empty($_POST['email'])) {
        $params['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    }

    // inscription ou désinscription
    if($sendui_action=='subscribe') {
        $sendui = RestClient::post($url_sendui,json_encode($params));
    } else {
        $sendui = RestClient::delete($url_sendui,$params);
    }

    // récupérer la reponse au format json
    $response = $sendui->getResponse();
    if(!empty($response)) {
        $response = json_decode($response);
        if(isset($response->message->user)) {
            $msg = $response->message->user;
        }
    }

    // code 400 => une erreur
    if($sendui->getResponseCode()==400) {
        echo '<span style="color:red;">'.$msg.'</span>';

    }
    // code 200 => c'est OK
    if($sendui->getResponseCode()==200) {
        echo '<span style="color:green;">'.$msg.'</span>';
    }

}
?>
<form method="post" action="test.php" id="sendui">
    <label id="email">Votre email <input type="text" name="email" /></label>
    <p><input type="submit" name="sendui_valid" value="inscription">
    <input type="hidden" name="sendui_action" value="unsubscribe" /></p>
</form>

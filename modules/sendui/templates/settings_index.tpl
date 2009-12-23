<div id="steps">
    <div class="step step-on">
        <div class="step-num">1</div>
        <div class="step-title">
            Réglages
        </div>
    </div>
    <div class="step">
        <div class="step-num">2</div>
        <div class="step-title">
            Composition
        </div>
    </div>
    <div class="step">
        <div class="step-num">3</div>
        <div class="step-title">
            Destinataires
        </div>
    </div>
    <div class="step">
        <div class="step-num">4</div>
        <div class="step-title">
            Vérification
        </div>
    </div>
    <div class="step">
    <div class="step-num">5</div>
        <div class="step-title">
            Envoi
        </div>
    </div>
</div>


<h2 class="main">Définition de l'expéditeur et du sujet</h2>

<div class="sendui-standard-content">

<div class="settings">
{form $message_settings, 'sendui~settings:save', array('idmessage' => $idmessage)}

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'name'}</div>
    <p>{ctrl_control 'name'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'subject'}</div>
    <p>{ctrl_control 'subject'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'from_name'}</div>
    <p>{ctrl_control 'from_name'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'from_email'}</div>
    <p>{ctrl_control 'from_email'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'reply_to'}</div>
    <p>{ctrl_control 'reply_to'}</p>
</div>

<p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Continuer" type="submit"></p>

{/form}
</div>
</div>

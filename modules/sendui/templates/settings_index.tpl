<div id="steps">
    <div class="sendui-float-step">
        <div><span class="ui-widget-header ui-state-active ui-corner-all sendui-padding-simple">1</span></div>
        <div class="step-title">
            Réglages
        </div>
    </div>
    <div class="sendui-float-step">
        <div><span class="ui-widget-header ui-state-disabled ui-corner-all sendui-padding-simple">2</span></div>
        <div class="step-title ui-state-disabled">
            Composition
        </div>
    </div>
    <div class="sendui-float-step">
        <div><span class="ui-widget-header ui-state-disabled ui-corner-all sendui-padding-simple">2</span></div>
        <div class="step-title ui-state-disabled">
            Destinataires
        </div>
    </div>
    <div class="sendui-float-step">
        <div><span class="ui-widget-header ui-state-disabled ui-corner-all sendui-padding-simple">2</span></div>
        <div class="step-title ui-state-disabled">
            Vérification
        </div>
    </div>
    <div class="sendui-float-step">
        <div><span class="ui-widget-header ui-state-disabled ui-corner-all sendui-padding-simple">2</span></div>
        <div class="step-title ui-state-disabled">
            Envoi
        </div>
    </div>
    <div class="spacer"></div>
</div>


<h2 class="mainpage newmessage float-left">Définition de l'expéditeur et du sujet</h2>

<div class="sendui-padding-mainpage float-right">
    <a href="{jurl 'sendui~subscribers:listview'}" class="fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-circle-plus"></span>Créer à partir d'un ancien message</a>
</div>

<div class="spacer"></div>

<div class="sendui-standard-content">

<div class="settings">
{form $message_settings, 'sendui~settings:save', array('idmessage' => $idmessage, 'from_page' => $from_page)}

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

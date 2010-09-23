<h2 class="main dashboard">Configuration du serveur d'envoi</h2>

<div class="sendui-standard-content">

<div class="settings">
{form $install_smtpmail, 'sendui~install:save_smtpmail'}

<div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
    <h3>Accès au serveur d'envoi</h3>
</div>

{formcontrols array('sSmtpHost','sSmtpUsername','sSmtpPassword')}
<div class="bloc-form ui-corner-all">
    <div>{ctrl_label}</div>
    <p>{ctrl_control}</p>
</div>
{/formcontrols}

<p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Continuer" type="submit" /></p>

{/form}
</div>

</div>



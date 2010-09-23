<h2 class="main dashboard">Création de l'utilisateur</h2>

<div class="sendui-standard-content">

<div class="settings">
{form $install_user, 'sendui~install:save_user'}

<div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
    <h3>Informations de connexion</h3>
</div>

{formcontrols array('login','email','password')}
<div class="bloc-form ui-corner-all">
    <div>{ctrl_label}</div>
    <p>{ctrl_control}</p>
</div>
{/formcontrols}

<p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Continuer" type="submit" /></p>

{/form}
</div>

</div>



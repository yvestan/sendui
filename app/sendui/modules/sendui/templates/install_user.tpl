<h2 class="main user-add">Création de l'utilisateur</h2>

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

    <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
        <h3>Paramètres d'expedition</h3>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'return_path'}</div>
        <p>{ctrl_control 'return_path'}</p>
    </div>

    <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
        <h3>Quotas d'expédition</h3>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'batch_quota'}</div>
        <p>{ctrl_control 'batch_quota'}</p>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'pause_quota'}</div>
        <p>{ctrl_control 'pause_quota'}</p>
    </div>

    <p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Continuer" type="submit" /></p>

{/form}
</div>

</div>



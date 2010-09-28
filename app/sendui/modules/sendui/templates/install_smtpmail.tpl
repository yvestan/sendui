<h2 class="main newmessage">Configuration du serveur d'envoi</h2>

<div class="sendui-standard-content">

{if isset($already_exists)}
    <div class="ui-state-error ui-corner-all sendui-padding-simple sendui-margin-bottom"> 
            <p><span class="ui-icon ui-icon-alert sendui-icon-float"></span> La configuration du serveur d'envoi existe déjà. 
            Vous devez modifier manuellement le fichier <em>app/sendui/var/config/cmdline/cli.ini.php</em></p>
    </div>
    <p><a href="{jurl 'sendui~install:user'}" class="forms-submit fg-button ui-state-default ui-corner-all">Étape suivante (configuration de l'utilisateur principal)</a></p>
{else}
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
{/if}

</div>



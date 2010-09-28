<h2 class="main database_gear">Configuration de la base de donnée</h2>

<div class="sendui-standard-content">

{if isset($already_exists)}
    <div class="ui-state-error ui-corner-all sendui-padding-simple sendui-margin-bottom"> 
            <p><span class="ui-icon ui-icon-alert sendui-icon-float"></span> La configuration de la base de données existe déjà. 
            Vous devez modifier manuellement le fichier <em>app/sendui/var/config/dbprofils.ini.php</em></p>
    </div>
    <p><a href="{jurl 'sendui~install:smtpmail'}" class="forms-submit fg-button ui-state-default ui-corner-all">Étape suivante (configuration du serveur d'envoi)</a></p>
{else}
    <div class="settings">
    {form $install_index, 'sendui~install:save_index'}

    <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
        <h3>Type de base de données</h3>
    </div>

    {formcontrols array('driver')}
    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label}&nbsp;{ctrl_control}</div>
    </div>
    {/formcontrols}

    <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
        <h3>Accès à la base de données</h3>
    </div>

    {formcontrols array('host','database','user','password')}
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



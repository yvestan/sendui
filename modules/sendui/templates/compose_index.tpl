<h2 class="mainpage newmessage">Votre compte</h2>

{literal}
<script type="text/javascript">
    $(function() {
        $("#tabs").tabs();
    });
    </script>
{/literal}

<div class="sendui-standard-content">

    <div id="tabs">

        <ul>
            <li><a href="#message-html">Rédiger le message</a></li>
            <li><a href="#file_upload">Charger depuis un fichier</a></li>
            <li><a href="#templates">Utiliser un modèle</a></li>
        </ul>

        <div id="message-html">
            {form $message_compose, 'sendui~compose:save', array('idmessage' => $idmessage)}

                <div>{ctrl_label 'html_message'}</div>
                <p>{ctrl_control 'html_message'}</p>

                <div>{ctrl_label 'text_message'}</div>
                <p>{ctrl_control 'text_message'}</p>

                <p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Enregistrer" type="submit"></p>

            {/form}
        </div>

        <div id="file_upload">
        
            <h3>Charger depuis un fichier HTML</h3>

            {form $message_compose, 'sendui~compose:save'}

                <div>{ctrl_label 'file_message'}</div>
                <p>{ctrl_control 'file_message'}</p>

                <p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Continuer" type="submit"></p>

            {/form}

        </div>

        <div id="templates">

            <h3>Choisissez parmis un modèle à éditer</h3>

            <div class="galeries templates">

            </div>

        </div>

    </div>

</div>

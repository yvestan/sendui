{if empty($from_page)}
    {$steps}
{/if}

<h2 class="mainpage newmessage">Composer votre message</h2>

{literal}
<script type="text/javascript">
    $(function() {
        $("#tabs-compose").tabs();
    });
    </script>
{/literal}

<script type="text/javascript" src="/sendui/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/sendui/js/ckeditor/config.js"></script>

<div class="sendui-standard-content">

    <div id="tabs-compose">

        <ul class="tabs-padding">
            <li><a href="#message-html" class="page-white-edit">Rédiger le message</a></li>
            <!--<li><a href="#file_upload" class="page-white-get">Charger depuis un fichier</a></li>-->
            <!--<li><a href="#templates" class="layout-edit">Utiliser un modèle</a></li>-->
        </ul>

        <div id="message-html">
            {form $message_compose, 'sendui~compose:save', array('idmessage' => $idmessage,'from_page' => $from_page)}

                <div class="page-white-code sendui-padding-simple">{ctrl_label 'html_message'}</div>
                <p>{ctrl_control 'html_message'}</p>

                <div class="spacer">&nbsp;</div>

                <div class="page-white sendui-padding-simple">{ctrl_label 'text_message'}</div>
                <p class="text_message_class ui-corner-all">{ctrl_control 'text_message'}</p>

                <div class="spacer">&nbsp;</div>

                <p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Enregistrer" type="submit"></p>

            {/form}
        </div>

        <!--<div id="file_upload">
        
            <h3 class="page-white-code">Charger depuis un fichier HTML</h3>
            <div class="spacer">&nbsp;</div>

            {form $message_compose, 'sendui~compose:save'}

                <div>{ctrl_label 'file_message'}</div>
                <p>{ctrl_control 'file_message'}</p>

                <div class="spacer">&nbsp;</div>

                <p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Charger" type="submit"></p>

            {/form}

        </div>-->

        <!--<div id="templates">

            <h3 class="layout-content">Choisissez parmis un modèle à éditer</h3>

            <div class="galeries templates sendui-padding-double">
                prochainement, une liste de modèle
            </div>

        </div>-->

    </div>

    {literal}
    <script type="text/javascript">
        $(document).ready(function() {
            CKEDITOR.replace( 'html_message', 'html_message' );
        });
    </script>
    {/literal}

</div>

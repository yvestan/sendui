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

            <div class="ui-state-highlight ui-corner-all sendui-padding-simple sendui-margin-bottom"> 
                <p><span class="ui-icon ui-icon-info sendui-icon-float"></span>N'oubliez pas d'ajouter le <span class="link_break"><strong>lien de désabonnement</strong></span>
                dans vos messages (dans la version HTML et dans la version texte).</p>
            </div>
    <div id="tabs-compose">

        <ul class="tabs-padding">
            <li><a href="#message-html" class="page-white-edit">Rédiger le message</a></li>
            <!--<li><a href="#file_upload" class="page-white-get">Charger depuis un fichier</a></li>-->
            <!--<li><a href="#templates" class="layout-edit">Utiliser un modèle</a></li>-->
        </ul>

        <div id="message-html">


            <p>Vous pouvez utiliser les tags suivant pour personnaliser chaque message :</p>
            <ul>
                <li>{literal}{_(email)_}{/literal} : Email de l'abonné</li>
                <li>{literal}{_(firstname)_}{/literal} : Prénom</li>
                <li>{literal}{_(lastname)_}{/literal} : Nom</li>
                <li>{literal}{_(token)_}{/literal} : Identifiant unique de l'abonné</li>
            </ul>

            <p>Pour ajouter le <span class="link_break">lien de désabonnement</span>, ajouter un lien hypertexte 
                qui contient l'<acronym title="Universal Ressources Locator">URL</acronym> :<br />
                http://</p>

            {form $message_compose, 'sendui~compose:save', array('idmessage' => $idmessage,'from_page' => $from_page)}

                <div class="page-white-code sendui-padding-simple">{ctrl_label 'html_message'}</div>
                <p>{ctrl_control 'html_message'}</p>

                <div class="spacer">&nbsp;</div>

                <div class="page-white sendui-padding-simple">{ctrl_label 'text_message'} <a href="#" class="unsubscribe_link link_break">ajouter le lien de désabonnement</a></div>
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

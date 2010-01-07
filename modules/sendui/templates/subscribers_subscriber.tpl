<h2 class="mainpage subscribers">Ajouter des abonnés à la liste</h2>
{if !empty($idsubscriber_list)}
    <h3 class="sendui-mainpage-h3">
        <a href="{jurl 'sendui~subscribers:listview', array('idsubscriber_list' => $idsubscriber_list, 'from_page' => 'sendui~subscribers:subscriber')}" 
            class="table-edit" title="Modifier les paramètres de la liste">{$subscriber_list->name}</a></h3>
{/if}

{literal}
<script type="text/javascript">
    $(function() {
        $("#tabs-compose").tabs();
    });
    </script>
{/literal}

<div class="sendui-standard-content">

    <div id="tabs-compose" class="sendui-tabs-content">

        <ul>
            <li><a href="#subscribers-text" class="page-white-edit">Ajout simple</a></li>
            <li><a href="#subscribers-file" class="page-white-get">Depuis un fichier</a></li>
            <li><a href="#subscribers-unique" class="layout-edit">Ajouter un seul abonné</a></li>
        </ul>

        <div id="subscribers-text">

            <h3 class="sendui-content">Manuellement ou par copier/coller</h3>

            <p class="sendui-content">Vous pouvez ajouter un abonné par ligne en séparant les différentes valeurs par des points virgules. 
                <strong>La première valeur de la ligne doit toujours être l'adresse email</strong>.
                Veillez à ne pas mettre des points virgules dans vos adresses. Exemple : </p>

            <ul>
                <li>moi@mondomaine.com;Monsieur Plus;Prénom;Nom</li>
                <li>autre@domaine.net</li>
            </ul>

            <p class="sendui-content">Pour ajouter plus d'abonnés en même temps, préférez <a href="#subscribers-file" class="page-white-get">l'ajout depuis un fichier</a></p>

            {form $form_subscribers_text, 'sendui~subscribers:subscribers_textsave', array('idsubscriber' => $idsubscriber, 'idsubscriber_list' => $idsubscriber_list, 'from_page' => $from_page)}

            <p class="text_message_class ui-corner-all">{ctrl_control 'subscribers'}</p>

            <div class="spacer">&nbsp;</div>

            <p><input name="_submit_text" id="form_subscribers_text" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Ajouter les abonnés" type="submit"></p>

            {/form}

        </div>

        <div id="subscribers-file">
            
            <h3 class="sendui-content">Depuis un fichier de type CSV (tableur)</h3>

            <p class="sendui-content"><strong>Important</strong> : les colonnes de votre fichier CSV doivent-être 
                séparées par des points virgules et les valeurs entourées par des guillemets.</p>

            <p>Exemple :</p>

            {form $form_subscribers_file, 'sendui~subscribers:subscribers_filesave', array('idsubscriber' => $idsubscriber, 'idsubscriber_list' => $idsubscriber_list, 'from_page' => $from_page)}

            <div>{ctrl_control 'file_subscribers'}</div>

            <div class="spacer">&nbsp;</div>

            <p><input name="_submit_text" id="form_subscribers_text" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Ajouter les abonnés" type="submit"></p>

            {/form}
        </div>

        <div class="settings" id="subscribers-unique">
        {form $form_subscriber, 'sendui~subscribers:subscribersave', array('idsubscriber' => $idsubscriber, 'idsubscriber_list' => $idsubscriber_list, 'from_page' => $from_page)}

        <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
            <h3>Identité</h3>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'email'}</div>
            <p>{ctrl_control 'email'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'fullname'}</div>
            <p>{ctrl_control 'fullname'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'firstname'}</div>
            <p>{ctrl_control 'firstname'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'lastname'}</div>
            <p>{ctrl_control 'lastname'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'address'}</div>
            <p>{ctrl_control 'address'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'phone'}</div>
            <p>{ctrl_control 'phone'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'mobile'}</div>
            <p>{ctrl_control 'mobile'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'zip'}</div>
            <p>{ctrl_control 'zip'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'country'}</div>
            <p>{ctrl_control 'country'}</p>
        </div>

        <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
            <h3>Informations sur l'abonnement</h3>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'status'}</div>
            <p>{ctrl_control 'status'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'confirmed'}</div>
            <p>{ctrl_control 'confirmed'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'html_format'}</div>
            <p>{ctrl_control 'html_format'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'text_format'}</div>
            <p>{ctrl_control 'text_format'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'subscribe_from'}</div>
            <p>{ctrl_control 'subscribe_from'}</p>
        </div>

        <p><input name="_submit" id="jforms_sendui_form_subscriber_submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Enregistrer" type="submit"></p>

        {/form}
        </div>

    </div>
</div>

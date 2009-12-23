<h2 class="mainpage newmessage">Sélection des destinataires</h2>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_subscribers_lists').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "oLanguage": { "sUrl": "/sendui/js/datatables/i18n/fr_FR.txt" }
        });
    });
</script>
{/literal} 

<div class="sendui-standard-content">

    <p>Cochez les listes de destinataire pour l'envoi du message</p>

    <form method="post" action="{jurl 'sendui~recipients:save', array('idmessage' => $idmessage, 'from_page' => $from_page)}" id="form_recipients">

    <table class="tabl display" id="tab_subscribers_lists">
        <thead>
            <tr>
                <th>Choisir</th>
                <th>Liste</th>
                <th>Nombre d'abonnés</th>
                <th>Dernier envoi</th>
            </tr>
        </thead>
        <tbody>
            {foreach $list_subscribers_lists as $subscriber_list}
            <tr>
                <td><input type="checkbox" name="idsubscriber_list[]" value="{$subscriber_list->idsubscriber_list}" 
                    {if $message_subscriber_list->isMessageList($subscriber_list->idsubscriber_list)>0 }
                        checked="checked"
                    {/if} /></td>
                <td>{$subscriber_list->name}</td>
                <td>{$subscriber_subscriber_list->countSubscriberByList($subscriber_list->idsubscriber_list)} abonnés</td>
                <td>{$subscriber_list->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>

    <div><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Continuer" type="submit"></div>

    </form>

</div>

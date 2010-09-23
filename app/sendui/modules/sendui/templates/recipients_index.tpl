{if empty($from_page)}
    {$steps}
{/if}

<h2 class="mainpage newmessage">Sélection des destinataires</h2>

<script type="text/javascript">
    var url_datatables_lng = '{$j_basepath}js/datatables/i18n/fr_FR.txt';
</script>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_subscribers_lists').dataTable({
            "bJQueryUI": true,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "oLanguage": { "sUrl": url_datatables_lng },
        });
    });
</script>
{/literal} 

<div class="sendui-standard-content">

    <p class="sendui-content">Choisissez une liste de destinataires pour l'envoi du message</p>

    <form method="post" action="{jurl 'sendui~recipients:save', array('idmessage' => $idmessage, 'from_page' => $from_page)}" id="form_recipients">

    <table class="tabl display" id="tab_subscribers_lists">
        <thead>
            <tr>
                <th>Liste</th>
                <th>Nombre d'abonnés</th>
                <th>Dernier envoi</th>
            </tr>
        </thead>
        <tbody>
            {foreach $list_subscribers_lists as $subscriber_list}
            {if $subscriber->countByList($subscriber_list->idsubscriber_list)>0}
            <tr class="highlight">
                <td>
                    {if $message_subscriber_list->isMessageList($subscriber_list->idsubscriber_list,$idmessage)>0 }
                        <input type="radio" name="idsubscriber_list" value="{$subscriber_list->idsubscriber_list}" checked="checked" />
                    {else}
                        <input type="radio" name="idsubscriber_list" value="{$subscriber_list->idsubscriber_list}" />
                    {/if}
                {$subscriber_list->name}</td>
                <td>{$subscriber->countByList($subscriber_list->idsubscriber_list)} abonnés</td>
                <td>{$subscriber_list->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
            </tr>
            {/if}
            {/foreach}
        </tbody>
    </table>

    <div class="sendui-margin-top"><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Continuer" type="submit" /></div>

    </form>

</div>


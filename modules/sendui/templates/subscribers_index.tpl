<h2 class="mainpage subscribers">Listes d'abonnés</h2>

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

    <a href="{jurl 'sendui~subscribers:listview'}">créer une nouvelle liste</a>

    <table class="tabl display" id="tab_subscribers_lists">
        <thead>
            <tr>

                <th>Liste</th>
                <th>Nombre d'abonnés</th>
                <th>Dernier envoi</th>
                <th>Crée le</th>
                <th>Actions</th>
            </tr>

        </thead>
        <tbody>
            {foreach $list_subscribers_lists as $subscriber_list}
            <tr class="highlight">
                <td class="edit"><a href="{jurl 'sendui~subscribers:view', array('idsubscriber_list' => $subscriber_list->idsubscriber_list)}">{$subscriber_list->name}</a></td>
                <td>
                    <a href="{jurl 'sendui~subscribers:view', array('idsubscriber_list' => $subscriber_list->idsubscriber_list)}">
                       {$dao_subscriber_list->countSubscriber($subscriber_list->idsubscriber_list)} abonnés</a>
                </td>
                <td>{$dao_subscriber_list->countSubscriber($subscriber_list->idsubscriber_list)}</td>
                <td>{$subscriber_list->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td class="center">
                    <a href="{jurl 'sendui~subscribers:view', array('idsubscriber_list' => $subscriber_list->idsubscriber_list)}">voir les abonnés</a>
                    <a href="{jurl 'sendui~subscribers:listview', array('idsubscriber_list' => $subscriber_list->idsubscriber_list)}">modifier</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>

</div>

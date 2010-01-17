<div class="sendui-list-simple-content ui-corner-all"
<table class="sendui-list-simple">
    <thead>
        <tr>
            <th>Nom de la liste</th>
            <th>Crée le</th>
        </tr>
    </thead>
    <tbody>
        {foreach $list_subscribers_lists as $subscriber_list}
        <tr class="highlight">
            <td>
                <a href="{jurl 'sendui~settings:index', array('idsubscriber_list' => $subscriber_list->idsubscriber_list)}">{$subscriber_list->name}</a>
                 ({$subscriber_subscriber_list->countSubscriberByList($subscriber_list->idsubscriber_list)} abonnés)
            </td>
            <td>{$subscriber_list->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
</div>

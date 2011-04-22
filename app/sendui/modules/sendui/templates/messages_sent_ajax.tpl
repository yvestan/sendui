<div class="sendui-list-simple-content ui-corner-all">
<table class="sendui-list-simple">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Sujet</th>
            <th>Expéditeur</th>
            <th>Envoyée le</th>
        </tr>
    </thead>
    <tbody>
        {foreach $list_sent as $message}
        <tr class="highlight">
            <td><a href="{jurl 'sendui~settings:index', array('idmessage' => $message->idmessage, 'reuse' => true)}" class="add">{$message->name}</a></td>
            <td>{$message->subject}</td>
            <td>{$message->from_name} [{$message->from_email}]</td>
            <td>{$message->sent_start|jdatetime:'db_datetime','lang_datetime'}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
</div>

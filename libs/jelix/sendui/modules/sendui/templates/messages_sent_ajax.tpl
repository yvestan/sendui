<div class="sendui-list-simple-content ui-corner-all"
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
            <td><a href="{jurl 'sendui~messages:preview', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="add">{$message->name}</a></td>
            <td>{$message->subject}</td>
            <td>{$message->from_name} {$message->from_email}</td>
            <td>{$message->sent|jdatetime:'db_datetime','lang_datetime'}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
</div>

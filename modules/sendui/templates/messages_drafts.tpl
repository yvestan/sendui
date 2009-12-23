<h2 class="mainpage drafts">Brouillons</h2>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_drafts').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "oLanguage": { "sUrl": "/sendui/js/datatables/i18n/fr_FR.txt" }
        });
    });
</script>
{/literal} 

<div class="sendui-standard-content">

    <table class="tabl display" id="tab_drafts">
        <thead>
            <tr>

                <th>Nom</th>
                <th>Sujet</th>
                <th>Expéditeur</th>
                <th>Modifié le</th>
                <th>Modifier &amp; envoyer</th>
            </tr>

        </thead>
        <tbody>
            {foreach $list_drafts as $message}
            <tr class="highlight">
                <td class="edit"><a href="{jurl 'sendui~settings:prepare', array('idmessage' => $message->idmessage)}">{$message->name}</a></td>
                <td>{$message->subject}</td>
                <td>{$message->from_name} {$message->from_email}</td>
                <td>{$message->date_update|jdatetime:'db_datetime','lang_datetime'}</td>
                <td class="center"><a href="{jurl 'sendui~settings:prepare', array('idmessage' => $message->idmessage)}">modifier &amp; envoyer</a></td>
            </tr>
            {/foreach}
        </tbody>
    </table>

</div>

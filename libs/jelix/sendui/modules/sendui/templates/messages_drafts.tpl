<h2 class="mainpage drafts">Brouillons</h2>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_drafts').dataTable({
            "bJQueryUI": true,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "aaSorting": [[3,'desc']],
            "oLanguage": { "sUrl": "/sendui/js/datatables/i18n/fr_FR.txt" }
        });
    });
</script>
{/literal} 

<div class="sendui-standard-content">

    <table class="tabl display" id="tab_drafts">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>Nom</th>
                <th>Sujet</th>
                <th>Expéditeur</th>
                <th style="width:150px;">Modifié le</th>
                <th style="width:190px;">Modifier &amp; envoyer</th>
            </tr>

        </thead>
        <tbody>
            {foreach $list_drafts as $message}
            <tr class="highlight">
                <td><a href="{jurl 'sendui~messages:preview', array('idmessage' => $message->idmessage)}" class="pencil nolink">&nbsp;</a></td>
                <td><a href="{jurl 'sendui~messages:preview', array('idmessage' => $message->idmessage)}">{$message->name}</a></td>
                <td>{$message->subject}</td>
                <td>{$message->from_name} {$message->from_email}</td>
                <td>{$message->date_update|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>
                    <a href="{jurl 'sendui~settings:prepare', array('idmessage' => $message->idmessage)}" class="newmessage">envoyer</a>
                    <a href="{jurl 'sendui~messages:draftdelete', array('idmessage' => $message->idmessage)}" class="table-delete confirm_action"
                        title="Êtes-vous sur de vouloir supprimer ce brouillon ?">supprimer</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>

    <ul class="legende">
        <li class="pencil">brouillon / en cours d'édition</li>
    </ul>
    <div class="spacer"></div>

</div>

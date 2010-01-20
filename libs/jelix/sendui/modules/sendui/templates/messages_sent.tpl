<h2 class="mainpage archives">Messages en cours et messages envoyés</h2>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_sent').dataTable({
            "bJQueryUI": true,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "oLanguage": { "sUrl": "/sendui/js/datatables/i18n/fr_FR.txt" }
        });
    });
</script>
{/literal} 

<div class="sendui-standard-content">

    <table class="tabl display" id="tab_sent">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>Nom</th>
                <th>Sujet</th>
                <th>Expéditeur</th>
                <th>Envoyée le</th>
                <th>Actions</th>
            </tr>

        </thead>
        <tbody>
            {foreach $list_sent as $message}
            <tr class="highlight">
                <td>
                    {if $message->status==1}
                        <span class="clock">&nbsp;</span>
                    {elseif $message->status==2}
                        <span class="package-go">&nbsp;</span>
                    {elseif $message->status==3}
                        <span class="clock-pause">&nbsp;</span>
                    {elseif $message->status==4}
                        <span class="clock">&nbsp;</span>
                    {elseif $message->status==5}
                        <span class="tick">&nbsp;</span>
                    {/if}
                </td>
                <td><a href="{jurl 'sendui~messages:preview', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~message:sent')}">{$message->name}</a></td>
                <td>{$message->subject}</td>
                <td>{$message->from_name} [{$message->from_email}]</td>
                <td>{$message->sent_start|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>
                    {if $message->status==1}
                        <a href="{jurl 'sendui~send:cancel', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="control-stop">annuler</a>
                    {/if}
                    {if $message->status==2}
                        <a href="{jurl 'sendui~send:stop', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="control-pause">suspendre</a>
                        <!--<a href="{jurl 'sendui~send:cancel', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="control-stop">annuler</a>-->
                    {/if}
                    {if $message->status==3}
                        <a href="{jurl 'sendui~send:start', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="package-go">reprendre</a>
                        <!--<a href="{jurl 'sendui~send:cancel', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="control-stop">annuler</a>-->
                    {/if}
                    {if $message->status==5}
                        <a href="{jurl 'sendui~settings:prepare', array('idmessage' => $message->idmessage, 'reuse' => 1)}" class="email-add">ré-utiliser</a>
                    {/if}
                    {if $message->status==5}
                    <a href="{jurl 'sendui~messages:delete', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:send')}" class="table-delete confirm_action"
                        title="Êtes-vous sur de vouloir supprimer ce message ? CETTE ACTION NE PEUT PAS ÊTRE ANNULÉE !">supprimer</a>
                    {/if}
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>

    <ul class="legende">
        <li class="clock">en attente [1]</li>
        <li class="package-go">en cours d'envoi [2]</li>
        <li class="clock-pause">envoyés partiellement [3]</li>
        <li class="clock-pause">en attente de ré-expédition [4]</li>
        <li class="tick">envoyés [5]</li>
    </ul>
    <div class="spacer"></div>

</div>

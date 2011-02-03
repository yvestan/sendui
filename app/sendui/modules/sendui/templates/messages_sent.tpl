<h2 class="mainpage archives">Messages en cours et messages envoyés</h2>

<script type="text/javascript">
    var url_datatables_fr = '{$j_basepath}js/datatables/i18n/fr_FR.txt';
</script>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_sent').dataTable({
            "bJQueryUI": true,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "oLanguage": { "sUrl": url_datatables_fr },
            "aaSorting": [[0, 'desc']]
        });
    });
</script>
{/literal} 

<div class="sendui-standard-content">

    {if !empty($delete)}
        <div class="ui-state-highlight ui-corner-all sendui-padding-simple sendui-margin-bottom"> 
                <p><span class="ui-icon ui-icon-info sendui-icon-float"></span>Le message a bien été supprimé.</p>
        </div>
    {/if}

    <table class="tabl display" id="tab_sent">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>Nom</th>
                <th>Sujet</th>
                <th>Envoyée le</th>
                <th>A</th>
                <th>Actions</th>
            </tr>

        </thead>
        <tbody>
            {foreach $list_sent as $message}
            <tr class="highlight">
                <td>
                    <span style="display:none;">{$message->sent_start}</span>
                    {if $message->status==1}
                        <span class="clock">&nbsp;</span>
                    {elseif $message->status==2}
                        <span class="ajax-loader">&nbsp;</span>
                    {elseif $message->status==3}
                        <span class="clock-pause">&nbsp;</span>
                    {elseif $message->status==4}
                        <span class="clock">&nbsp;</span>
                    {elseif $message->status==5}
                        <span class="tick">&nbsp;</span>
                    {/if}
                </td>
                <td><a href="{jurl 'sendui~messages:preview', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~message:sent')}" 
                        title="{$message->name}">{$message->name|truncate:25}</a></td>
                <td class="sendui-small">{$message->subject|truncate:25}
                    <!--<br />{$message->from_name} <span class="sendui-grey">[{$message->from_email}]</span>-->
                </td>
                <td>{$message->sent_start|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>{if $message->status==2}En cours à{else}{$message->count_recipients} <span class="sendui-small sendui-grey">sur</span> {/if} 
                    {$message->total_recipients} <span class="sendui-small sendui-grey">destinataire(s)</td>
                <td>
                    {if $message->status==1}
                        <a href="{jurl 'sendui~send:cancel', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="control-stop">annuler</a>
                    {/if}
                    {if $message->status==2}
                        <a href="{jurl 'sendui~send:stop', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="control-pause">suspendre</a>
                        <!--<a href="{jurl 'sendui~send:cancelall', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="control-stop">annuler complètement</a>-->
                    {/if}
                    {if $message->status==3}
                        <a href="{jurl 'sendui~send:start', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="package-go">reprendre</a>
                        <a href="{jurl 'sendui~send:cancelall', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="cross">annuler complètement</a>
                    {/if}
                    {if $message->status==5}
                        <a href="{jurl 'sendui~settings:prepare', array('idmessage' => $message->idmessage, 'reuse' => 1)}" class="email-add">ré-utiliser</a>
                    {/if}
                    {if $message->status==5}
                    <a href="{jurl 'sendui~messages:delete', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:sent')}" class="table-delete confirm_action"
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

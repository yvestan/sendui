<div class="float-left">
    <h2 class="mainpage subscribers">Abonnés {if !empty($subscriber_list->name)}à la liste{/if}</h2>
    {if !empty($idsubscriber_list)}
        <h3 class="sendui-mainpage-h3">
            <a href="{jurl 'sendui~subscribers:listview', array('idsubscriber_list' => $idsubscriber_list, 'from_page' => 'sendui~subscribers:view')}" 
                class="table-edit" title="Modifier les paramètres de la liste">{$subscriber_list->name}</a></h3>
    {/if}
</div>

<div class="sendui-padding-mainpage float-right">
    <a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber_list' => $idsubscriber_list)}" class="fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-circle-plus"></span>Ajouter des abonnés</a>

    <!--<a href="{jurl 'sendui~subscribers:listexport', array('idsubscriber_list' => $idsubscriber_list)}" class="fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-disk"></span>Télécharger la liste (format CSV / excel)</a>-->
</div>

<div class="spacer"></div>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_subscribers_lists').dataTable({
            "bJQueryUI": true,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "oLanguage": { "sUrl": "/sendui/js/datatables/i18n/fr_FR.txt" },
            "aaSorting": [[3, 'desc']]
        });
    });
</script>
{/literal} 

<div class="sendui-standard-content">

    <table class="tabl display" id="tab_subscribers_lists">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>Email</th>
                <th>Dernier envoi</th>
                <th>Inscrit depuis le</th>
                <th>Actions</th>
            </tr>

        </thead>
        <tbody>
            {foreach $list_subscribers as $subscriber}
            <tr class="highlight">
                <td>
                    {if $subscriber->status==1}<span class="flag-green">&nbsp;</span>{/if}
                    {if $subscriber->status==0}<span class="flag-blue">&nbsp;</span>{/if}
                    {if $subscriber->status==2}<span class="flag-yellow">&nbsp;</span>{/if}
                    {if $subscriber->status==3}<span class="flag-red">&nbsp;</span>{/if}
                </td>
                <td>
                    {if $subscriber->status==3}
                        <del><a href="{jurl 'sendui~subscriber:index', array('idsubscriber' => $subscriber->idsubscriber, 'from_page' => 'sendui~subscribers:view', 'idsubscriber_list' => $idsubscriber_list)}">{$subscriber->email}</a></del>
                    {else}
                        <a href="{jurl 'sendui~subscriber:index', array('idsubscriber' => $subscriber->idsubscriber, 'from_page' => 'sendui~subscribers:index', 'idsubscriber_list' => $idsubscriber_list)}">{$subscriber->email}</a>
                    {/if}
                </td>
                <td>{if !empty($list->getLastSubscriberSent($subscriber->idsubscriber,$idsubscriber_list)->sent_date)}
                    {$list->getLastSubscriberSent($subscriber->idsubscriber,$idsubscriber_list)->sent_date|jdatetime:'db_datetime','lang_datetime'}
                    {else}
                        Aucun envoi
                    {/if}
                </td>
                <td>{$subscriber->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>
                    <a href="{jurl 'sendui~subscriber:prepare', 
                        array('idsubscriber' => $subscriber->idsubscriber, 'from_page' => 'sendui~subscribers:view', 'idsubscriber_list' => $idsubscriber_list)}" class="table-edit">modifier</a>
                    <a href="{jurl 'sendui~subscribers:deletesubscriber', array('idsubscriber' => $subscriber->idsubscriber, 'idsubscriber_list' => $idsubscriber_list)}" 
                        class="confirm_action table-delete" title="Êtes-vous sur de vouloir supprimer cet abonné ? CETTE ACTION NE PEUT PAS ÊTRE ANNULÉE !">supprimer</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>

    
    <ul class="legende">
        <li class="flag-green">actif</li>
        <li class="flag-blue">inactif (suspendu)</li>
        <li class="flag-yellow">à supprimer/vérifier (rebond)</li>
        <li class="flag-red">supprimé (à confirmer)</li>
    </ul>
    <div class="spacer">&nbsp;</div>

    <h3 class="sendui-margin-top"><a href="{jurl 'sendui~subscribers:generateform', array('idsubscriber_list' => $idsubscriber_list)}" class="application-form-add">
        Créer un formulaire d'abonnement</a> pour votre site</h3>


</div>

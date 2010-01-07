<div class="float-left">
    <h2 class="mainpage subscribers">Liste des abonnés {if !empty($subscriber_list->name)}à la liste{/if}</h2>
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
            "oLanguage": { "sUrl": "/sendui/js/datatables/i18n/fr_FR.txt" }
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
                        <del><a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber' => $subscriber->idsubscriber)}">{$subscriber->email}</a></del>
                    {else}
                        <a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber' => $subscriber->idsubscriber)}">{$subscriber->email}</a>
                    {/if}
                </td>
                <td>{if !empty($list->getLastSubscriberSent($subscriber->idsubscriber,$idsubscriber_list)->sent)}
                    {$list->getLastSubscriberSent($subscriber->idsubscriber,$idsubscriber_list)->sent|jdatetime:'db_datetime','lang_datetime'}
                    {else}
                        Aucun envoi
                    {/if}
                </td>
                <td>{$subscriber->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>
                    <a href="{jurl 'sendui~subscribers:preparesubscriber', 
                        array('idsubscriber' => $subscriber->idsubscriber, 'from_page' => 'sendui~subscribers:view', 'idsubscriber_list' => $idsubscriber_list)}" class="table-edit">modifier</a>
                    <a href="{jurl 'sendui~subscribers:deletesubscriber', array('idsubscriber' => $subscriber->idsubscriber, 'idsubscriber_list' => $idsubscriber_list)}" 
                        class="confirm_action table-delete" title="Êtes-vous sur de vouloir supprimer cet abonné ? CETTE ACTION NE PEUT PAS ÊTRE ANNULÉE !">supprimer</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>


                    <span class="flag-green">actif</span>
                    <span class="flag-blue">inactif (suspendu)</span>
                    <span class="flag-yellow">à supprimer (rebond)</span>
                    <span class="flag-red">supprimé</span>

    <h3 class="sendui-margin-top"><a href="{jurl 'sendui~subscribers:generateform', array('idsubscriber_list' => $idsubscriber_list)}" class="application-form-add">
        Créer un formulaire d'abonnement</a> pour votre site</h3>


</div>

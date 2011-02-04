<div class="float-left">
    <h2 class="mainpage subscribers">Abonnés {if !empty($subscriber_list->name)}à la liste{/if}</h2>
    {if !empty($idsubscriber_list)}
        <h3 class="sendui-mainpage-h3">
            <a href="{jurl 'sendui~subscribers:listview', array('idsubscriber_list' => $idsubscriber_list, 'from_page' => 'sendui~subscribers:view')}" 
                class="table-edit" title="Modifier les paramètres de la liste">{$subscriber_list->name}</a></h3>
    {/if}
</div>

<div class="sendui-padding-mainpage float-right">
    <div class="fg-buttonset fg-buttonset-multi">
    <a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber_list' => $idsubscriber_list)}" class="fg-button ui-state-active fg-button-icon-left ui-corner-left">
        <span class="ui-icon ui-icon-circle-plus"></span>Ajouter des abonnés</a>
    <a href="{jurl 'sendui~subscribers:exportcsv', array('idsubscriber_list' => $idsubscriber_list)}" class="fg-button ui-state-active fg-button-icon-left ui-corner-right">
        <span class="ui-icon ui-icon-disk"></span>Exporter (format CSV/Excel)</a>
    </div>
</div>

<div class="spacer"></div>

<script type="text/javascript">
    var url_datatables_lng = '{$j_basepath}js/datatables/i18n/fr_FR.txt';
    var url_ajax_source = '{jurl 'sendui~subscribers:view_list', array('idsubscriber_list' => $idsubscriber_list)}';
</script>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_subscribers_lists').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": url_ajax_source,
            "bJQueryUI": true,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "oLanguage": { "sUrl": url_datatables_lng },
            "aaSorting": [[3, 'desc']],
            // 5eme colonne non triable
            "aoColumnDefs": [{"bSortable": false, "aTargets": [4]}]
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
            <tr class="highlight">
                <td colspan="5" class="dataTables_empty">Chargement de la liste...</td>
            </tr>
        </tbody>
    </table>

    <ul class="legende">
        <li class="flag-green">actif</li>
        <li class="flag-blue">inactif (suspendu)</li>
        <li class="flag-yellow">rebond : à supprimer/vérifier</li>
        <li class="flag-red">supprimé (à confirmer)</li>
    </ul>
    <div class="spacer">&nbsp;</div>

    <h3 class="sendui-margin-top"><a href="{jurl 'sendui~subscribers:generateform', array('idsubscriber_list' => $idsubscriber_list)}" class="application-form-add">
        Créer un formulaire d'abonnement</a> pour votre site</h3>

    <h3 class="sendui-margin-top"><a href="{jurl 'sendui~subscribers:listpurge', array('idsubscriber_list' => $idsubscriber_list, 'from_page' => 'sendui~subscribers:view')}" 
    title="Êtes-vous sur de vouloir supprimer cet abonné ? CETTE ACTION NE PEUT PAS ÊTRE ANNULÉE !" class="confirm_action user-delete">
        Supprimer définitivement les abonnés marqués comme &laquo; supprimé &raquo;</a> (action irréversible)</h3>

</div>

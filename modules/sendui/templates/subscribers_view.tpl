<div id="navigation">
    <ul>
        <li><a href="{jurl 'default:index'}">tableau de bord</a></li>
        <li><a href="{jurl 'default:index'}">gérer vos listes d'abonnés</a></li>
        <li>comment rendre...</li>
    </ul>
</div>

<h2 class="mainpage subscribers float-left">Liste des abonnés {if !empty($subscriber_list->name)}à la liste{/if}</h2>

<div class="sendui-padding-mainpage float-right">
    <a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber_list' => $idsubscriber_list)}" class="fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-circle-plus"></span>Ajouter des abonnés</a>
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

                <th>Email</th>
                <th>Dernier envoi</th>
                <th>Inscrit depuis le</th>
                <th>Actions</th>
            </tr>

        </thead>
        <tbody>
            {foreach $list_subscribers as $subscriber}
            <tr class="highlight">
                <td><a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber' => $subscriber->idsubscriber)}">{$subscriber->email}</a></td>
                <td>{$subscriber->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>{$subscriber->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>
                    <a href="{jurl 'sendui~subscribers:preparesubscriber', 
                        array('idsubscriber' => $subscriber->idsubscriber, 'from_page' => 'sendui~subscribers:view', 'idsubscriber_list' => $idsubscriber_list)}" class="table-edit">modifier</a>
                    <a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber' => $subscriber->idsubscriber)}" class="table-delete">supprimer</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>

</div>

<h2 class="mainpage subscribers float-left">Listes d'abonnés</h2>

<div class="sendui-padding-mainpage float-right">
    <a href="{jurl 'sendui~subscribers:listview', array('from_page' => 'sendui~subscribers:index')}" class="fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-circle-plus"></span>Créer une nouvelle liste</a>
</div>

<div class="spacer"></div>

<script type="text/javascript">
    var url_datatables_lng = '{$j_basepath}js/datatables/i18n/fr_FR.txt';
</script>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_subscribers_lists').dataTable({
            "bJQueryUI": true,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "oLanguage": { "sUrl": url_datatables_lng },
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
                <th>Liste</th>
                <th>Nombre d'abonnés</th>
                <th>Crée le</th>
                <th>Mise à jour le</th>
                <th>Actions</th>
            </tr>

        </thead>
        <tbody>
            {foreach $list_subscribers_lists as $subscriber_list}
            <tr class="highlight">
                <td>
                    {if $subscriber_list->status==1}
                        <span class="tick">&nbsp;</span>
                    {else}
                        <span class="cross">&nbsp;</span>
                    {/if}
                </td>
                <td><a href="{jurl 'sendui~subscribers:view', array('idsubscriber_list' => $subscriber_list->idsubscriber_list)}">{$subscriber_list->name}</a></td>
                <td>
                    <a href="{jurl 'sendui~subscribers:view', array('idsubscriber_list' => $subscriber_list->idsubscriber_list)}">
                       {$subscriber->countByList($subscriber_list->idsubscriber_list)} abonnés</a>
                </td>
                <td>{$subscriber_list->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>{$subscriber_list->date_update|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>
                    <a href="{jurl 'sendui~subscribers:view', array('idsubscriber_list' => $subscriber_list->idsubscriber_list)}" class="table-go">voir les abonnés</a>
                    <a href="{jurl 'sendui~subscribers:listview', array('idsubscriber_list' => $subscriber_list->idsubscriber_list, 'from_page' => 'sendui~subscribers:index')}" class="table-edit">modifier</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>

    <ul class="legende">
        <li class="tick">active</li>
        <li class="cross">inactive</li>
    </ul>
    <div class="spacer"></div>

    <h3 class="sendui-margin-top"><a href="{jurl 'sendui~bouncescheck:index', array('from_page' => 'subscribers~index')}" class="flag-yellow">
        Gérer les rebonds</a> (adresses invalides ou bloqués)</h3>

</div>

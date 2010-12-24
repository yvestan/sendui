<h2 class="mainpage folder-bug float-left">Listes des boîtes de retour (bounces)</h2>

<div class="sendui-padding-mainpage float-right">
    <a href="{jurl 'sendui~bouncescheck:edit', array('from_page' => 'sendui~bouncescheck:index')}" class="fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-circle-plus"></span>Ajouter une boîte de retour</a>
</div>

<div class="spacer"></div>

<script type="text/javascript">
    var url_datatables_lng = '{$j_basepath}js/datatables/i18n/fr_FR.txt';
</script>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_bounce_list').dataTable({
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

    <table class="tabl display" id="tab_bounce_list">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>Catégorie</th>
                <th>N&deg; règle</th>
                <th>Email</th>
                <th>Diagnostic code</th>
                <th>Dernier retour</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {foreach $list_bounce as $bounce}
            <tr class="highlight">
                <td>
                    <span class="cross">&nbsp;</span>
                </td>
                <td>{$bounce->rule_cat}</td>
                <td>{$bounce->rule_no}</td>
                <td>{$bounce->email}</td>
                <td>{$bounce->diag_code}</td>
                <td>{$bounce->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>
                    <a href="{jurl 'sendui~bouncescheck:check', array('idbounce' => $bounce->idbounce, 'from_page' => 'sendui~bouncescheck:index')}" class="table-go">lancer l'analyse</a>
                    <a href="{jurl 'sendui~bouncescheck:edit', array('idbounce' => $bounce->idbounce, 'from_page' => 'sendui~bouncescheck:index')}" class="table-edit">modifier</a>
                    <a href="{jurl 'sendui~bouncescheck:delete', array('idbounce' => $bounce->idbounce, 'from_page' => 'sendui~bouncescheck:index')}" class="table-delete">supprimer</a>
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

</div>

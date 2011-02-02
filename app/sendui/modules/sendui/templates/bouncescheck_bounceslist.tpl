<h2 class="mainpage folder-bug float-left">Listes des rebonds (bounces)</h2>

<div class="sendui-padding-mainpage float-right">
    <a href="{jurl 'sendui~bouncescheck:syncbounce', array('from_page' => 'sendui~bouncescheck:bounceslist')}" class="fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-circle-plus"></span>Synchroniser avec les abonnés</a>
</div>

<div class="spacer"></div>

<script type="text/javascript">
    var url_datatables_lng = '{$j_basepath}js/datatables/i18n/fr_FR.txt';
    //var url_ajax_source = '{jurl 'sendui~bouncescheck:bounceslist_list'}';
</script>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_bounce_list').dataTable({
            /*"bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": url_ajax_source,*/
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

    <form method="post" action="{jurl 'sendui~bouncescheck:deletesubscribers', array('from_page' => 'bouncescheck~bounceslist')}" id="bouncedelete">

        <table class="tabl display" id="tab_bounce_list">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Catégorie</th>
                    <th>N&deg; règle</th>
                    <th>Email</th>
                    <th>Sévérité</th>
                    <!--<th style="width:200px;">Diagnostic code</th>-->
                    <th>Dernier rebond</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {foreach $list_bounce as $bounce}
                <tr class="highlight">
                    <td>
                        <input type="checkbox" name="email[]" value="{$bounce->email}" />
                    </td>
                    <td><a href="{jurl 'sendui~bouncescheck:bounceslist', array('rule_cat' => $bounce->rule_cat)}">{$bounce->rule_cat}</a></td>
                    <td class="sendui-small"><a href="{jurl 'sendui~bouncescheck:bounceslist', array('rule_no' => $bounce->rule_no)}">{$bounce->rule_no}</a></td>
                    <td>{$bounce->email}<br />
                        <span class="sendui-small">{$bounce->diag_code|truncate:30}</span>
                    <td><a href="{jurl 'sendui~bouncescheck:bounceslist', array('bounce_type' => $bounce->bounce_type)}">{$bounce->bounce_type}</a></td>
                    <td class="sendui-small">{$bounce->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                    <td>
                        <a href="{jurl 'sendui~bouncescheck:bounce', array('idbounce' => $bounce->idbounce, 'from_page' => 'sendui~bouncescheck:bounceslist')}" class="table-go">détails</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>

        <div class="sendui-margin-top">Pour la sélection :
            <select name="action_bounces" id="action_bounces">
                <option value="delete_subscribers">supprimer les abonnés</option>
                <option value="delete_bounces">supprimer les rebonds</option>
            </select>
            <input name="_submit" id="jforms_sendui_bounceslist_submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Éxecuter" type="submit"></div>

    </form>

    <ul class="legende">
        <li class="flag-yellow">rebond</li>
    </ul>
    <div class="spacer"></div>

</div>

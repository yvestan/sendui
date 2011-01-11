<h2 class="mainpage folder-bug float-left">Listes des boîtes de rebonds (bounces)</h2>

<div class="sendui-padding-mainpage float-right">
    <a href="{jurl 'sendui~bouncescheck:edit', array('from_page' => 'sendui~bouncescheck:index')}" class="fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-circle-plus"></span>Ajouter une boîte de rebonds</a>
</div>

<div class="spacer"></div>

<script type="text/javascript">
    var url_datatables_lng = '{$j_basepath}js/datatables/i18n/fr_FR.txt';
</script>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_bounce_config').dataTable({
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

    <table class="tabl display" id="tab_bounce_config">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>Nom</th>
                <th>Hôte</th>
                <th>Vérifiée le</th>
                <th>Crée le</th>
                <th>Actions</th>
            </tr>

        </thead>
        <tbody>
            {foreach $list_bounce_config as $bounce_config}
            <tr class="highlight">
                <td>
                    {if $bounce_config->status==1}
                        <span class="tick">&nbsp;</span>
                    {else}
                        <span class="cross">&nbsp;</span>
                    {/if}
                </td>
                <td><a href="{jurl 'sendui~bouncescheck:edit', array('idbounce_config' => $bounce_config->idbounce_config)}">{$bounce_config->name}</a></td>
                <td>{$bounce_config->mail_host}</td>
                <td>{if !empty($bounce_config->last_use)}{$bounce_config->last_use|jdatetime:'db_datetime','lang_datetime'}{else}jamais{/if}</td>
                <td>{$bounce_config->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>
                    <a href="{jurl 'sendui~bouncescheck:check', array('idbounce_config' => $bounce_config->idbounce_config, 'from_page' => 'sendui~bouncescheck:index')}" class="table-go">lancer l'analyse</a>
                    <a href="{jurl 'sendui~bouncescheck:edit', array('idbounce_config' => $bounce_config->idbounce_config, 'from_page' => 'sendui~bouncescheck:index')}" class="table-edit">modifier</a>
                    <a href="{jurl 'sendui~bouncescheck:delete', array('idbounce_config' => $bounce_config->idbounce_config, 'from_page' => 'sendui~bouncescheck:index')}" class="table-delete">supprimer</a>
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

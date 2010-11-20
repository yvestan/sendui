<h2 class="mainpage vcard float-left">Listes des utilisateurs</h2>

<div class="sendui-padding-mainpage float-right">
    <a href="{jurl 'sendui~globaladmin:user', array('from_page' => 'sendui~globaladmin:userlist')}" class="fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-circle-plus"></span>Ajouter un utilisateur</a>
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
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Quota</th>
                <th>Crée le</th>
                <th>Actions</th>
            </tr>

        </thead>
        <tbody>
            {foreach $customers_list as $customer}
            <tr class="highlight">
                <td>
                    {if $customer->active==1}
                        <span class="tick">&nbsp;</span>
                    {else}
                        <span class="cross">&nbsp;</span>
                    {/if}
                </td>
                <td><a href="{jurl 'sendui~globaladmin:user', array('idcustomer' => $customer->idcustomer)}">{$customer->login}</a></td>
                <td>
                    <a href="mailto:{$customer->email}">{$customer->email}</a>
                </td>
                <td>Lot : {$customer->batch_quota} mails / Pause : {$customer->pause_quota} sec.</td>
                <td>{$customer->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>
                    <a href="{jurl 'sendui~globaladmin:user', array('idcustomer' => $customer->idcustomer, 'from_page' => 'sendui~globaladmin:userlist')}" class="vcard-edit">modifier</a>
                    <a href="{jurl 'sendui~globaladmin:userdelete', array('idcustomer' => $customer->idcustomer, 'from_page' => 'sendui~globaladmin:userlist')}" class="vcard-delete">supprimer</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>

    <ul class="legende">
        <li class="tick">actif</li>
        <li class="cross">inactif</li>
    </ul>
    <div class="spacer"></div>

</div>

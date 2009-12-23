<div id="navigation">
    <ul>
        <li><a href="{jurl 'default:index'}">tableau de bord</a></li>
        <li><a href="{jurl 'default:index'}">gérer vos listes d'abonnés</a></li>
        <li>comment rendre...</li>
    </ul>
</div>

<h2 class="mainpage subscribers">Liste des abonnés {if !empty($subscriber_list->name)} à <span class="title-light">{$subscriber_list->name}</span>{/if}</h2>

{literal} 
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tab_subscribers_lists').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "oLanguage": { "sUrl": "/sendui/js/datatables/i18n/fr_FR.txt" }
        });
    });
</script>
{/literal} 

<div class="sendui-standard-content">

    <a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber_list' => $idsubscriber_list)}">ajouter des abonnés</a>
    <!--<a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber_list' => $idsubscriber_list)}">exporter la liste au format CSV</a>-->

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
                <td class="edit"><a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber' => $subscriber->idsubscriber)}">{$subscriber->email}</a></td>
                <td>{$subscriber->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td>{$subscriber->date_insert|jdatetime:'db_datetime','lang_datetime'}</td>
                <td class="center">
                    <a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber' => $subscriber->idsubscriber)}">modifier</a>
                    <a href="{jurl 'sendui~subscribers:preparesubscriber', array('idsubscriber' => $subscriber->idsubscriber)}">supprimer</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>

</div>

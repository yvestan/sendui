{if !empty($idsubscriber_list)}
    <h2 class="mainpage subscribers">Résultat de l'ajout d'abonnés à la liste</h2>
    <h3 class="sendui-mainpage-h3">
        <a href="{jurl 'sendui~subscribers:listview', array('idsubscriber_list' => $idsubscriber_list, 'from_page' => 'sendui~subscribers:view')}" 
            class="table-edit" title="Modifier les paramètres de la liste">{$subscriber_list->name}</a></h3>
{/if}

<div class="sendui-standard-content">

    <div class="ui-state-highlight ui-corner-all sendui-padding-simple sendui-margin-top"> 
        <p><span class="ui-icon ui-icon-info sendui-icon-float"></span>
            {if $nb_success>1}
                {$nb_success} abonnés ont été ajoutés à la liste
            {/if}
            {if $nb_success==1}
                {$nb_success} abonné a été ajouté à la liste
            {/if}
            {if $nb_success>1}
                Aucun abonné n'a été ajouté à la liste
            {/if}
        </p>
    </div>

    {if count($results['error'])>0}
    <div class="ui-state-error ui-corner-all sendui-padding-simple sendui-margin-top"> 
        <p><span class="ui-icon ui-icon-alert sendui-icon-float"></span> Les utilisateurs suivants n'ont pas pu être ajouté</p>
    </div>

    <ul class="sendui-liste">
        {foreach $results['error'] as $k=>$e}
            <li>[{$e[0]}] &raquo; {$e['error']}</li>
        {/foreach}
    </ul>
    {/if}

    {if !empty($idsubscriber_list)}
    <div class="sendui-margin-top-double">
        <a href="{jurl 'sendui~subscribers:view', array('idsubscriber_list' => $idsubscriber_list)}" class="arrow-left">retour à la liste</a>
        &nbsp;&nbsp;<a href="{jurl 'sendui~subscribers:subscriber', array('idsubscriber_list' => $idsubscriber_list)}" class="user-add">ajouter d'autres abonnés</a>
    </div>
    {/if}

</div>

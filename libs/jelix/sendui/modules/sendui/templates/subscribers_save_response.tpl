{if !empty($idsubscriber_list)}
    <h2 class="mainpage subscribers">Résultat de l'ajout d'abonnés à la liste</h2>
    <h3 class="sendui-mainpage-h3">
        <a href="{jurl 'sendui~subscribers:listview', array('idsubscriber_list' => $idsubscriber_list, 'from_page' => 'sendui~subscribers:view')}" 
            class="table-edit" title="Modifier les paramètres de la liste">{$subscriber_list->name}</a></h3>
{/if}

<div class="sendui-standard-content">

    <h3 class=""> ont été ajouté à la liste</h3>

    <h3>Les utilisateurs suivants n'ont pas pu être ajouté</h3>

    <ul>
        <li></li>
    </ul>

</div>

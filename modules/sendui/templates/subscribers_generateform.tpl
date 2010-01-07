<div class="float-left">
    <h2 class="mainpage subscribers">Formulaire d'abonnement sur votre site</h2>
    {if !empty($idsubscriber_list)}
        <h3 class="sendui-mainpage-h3">
            <a href="{jurl 'sendui~subscribers:listview', array('idsubscriber_list' => $idsubscriber_list, 'from_page' => 'sendui~subscribers:view')}" 
                class="table-edit" title="Modifier les paramètres de la liste">{$subscriber_list->name}</a></h3>
    {/if}
</div>

<div class="spacer"></div>

<div class="sendui-standard-content">

    <h3 class="sendui-content">Utilisez le code HTML suivant</h3>

    <p class="code text_message_class ui-corner-all">
<textarea cols="40" rows="10" style="width:900px;">
<form action="{jurl 'sendui~subscriber:public'}" method="post" id="sendui_form_{$customer->public_token}">
    <div>
        <label for="name_form_{$customer->public_token}">Name :</label> <input type="text" name="name_form_{$customer->public_token}" id="name_form_{$customer->public_token}" />
    </div>
    <div>
        <input type="hidden" name="list_{$subscriber_list->token}_{$customer->public_token}" value="1" />
        <input type="submit" id="submit_form_{$customer->public_token}" name="submit_form_{$customer->public_token}" value="Abonnement" />
    </div>
</form>
</textarea>
    </p>

    {if !empty($idsubscriber_list)}
    <div class="sendui-margin-top">
        <a href="{jurl 'sendui~subscribers:view', array('idsubscriber_list' => $idsubscriber_list)}" class="arrow-left">retour à la liste</a>
    </div>
    {/if}

</div>

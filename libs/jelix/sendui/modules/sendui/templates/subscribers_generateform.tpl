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
<form action="{$url_public}" method="post" id="sendui_form_{$subscriber_list->token}">
    <div>
        <label for="email_{$subscriber_list->token}">Email </label> 
        <input type="text" name="email_{$subscriber_list->token}" id="email_{$subscriber_list->token}" />
    </div>
    <div>
        <input type="hidden" name="sendui_token" value="list_{$subscriber_list->token}_{$customer->public_token}" />
        <input type="submit" id="submit_form_{$subscriber_list->token}" name="submit_form_{$subscriber_list->token}" value="Abonnement" />
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

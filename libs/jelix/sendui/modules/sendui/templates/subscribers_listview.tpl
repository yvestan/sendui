{if empty($idsubscriber_list)}
    <h2 class="mainpage subscribers">Créer une liste</h2>
{else}
    <h2 class="mainpage subscribers">Gérer la liste</h2>
    <h3 class="sendui-mainpage-h3">{$subscriber_list->name}</h3>
{/if}

<div class="sendui-standard-content">

<div class="settings">
{form $form_subscriber_list, 'sendui~subscribers:listsave', array('idsubscriber_list' => $idsubscriber_list, 'from_page' => $from_page)}

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'name'}</div>
    <p>{ctrl_control 'name'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'description'}</div>
    <p>{ctrl_control 'description'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'status'} {ctrl_control 'status'}</div>
</div>

<p class="sendui-margin-top">
    <input name="_submit" id="jforms_sendui_form_subscriber_list_submit" 
        class="jforms-submit fg-button ui-state-default ui-corner-all" value="Enregistrer" type="submit">
    {if !empty($from_page)}
        <a href="{jurl $from_page, array('idsubscriber_list' => $idsubscriber_list)}" class="cancel cross">annuler</a>
    {/if}
</p>

{/form}
</div>

{if !empty($idsubscriber_list)}
<p class="sendui-margin-top-double">
    <a href="{jurl 'sendui~subscribers:listdelete', array('idsubscriber_list' => $idsubscriber_list)}" 
        class="confirm_action table-delete" title="Êtes-vous sur de vouloir supprimer cette liste ? CETTE ACTION NE PEUT PAS ÊTRE ANNULÉE !">Supprimer cette liste</a>
</p>
{/if}

</div>

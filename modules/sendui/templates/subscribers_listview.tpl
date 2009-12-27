{if empty($idsubscriber_list)}
    <h2 class="mainpage subscribers">Créer une liste</h2>
{else}
    <h2 class="mainpage subscribers">Gérer la liste</h2>
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

<p>
    <input name="_submit" id="jforms_sendui_form_subscriber_list_submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Enregistrer" type="submit">
</p>

{/form}
</div>
</div>

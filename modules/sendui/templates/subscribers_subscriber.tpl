<h2 class="mainpage subscribers">Gérer la liste</h2>

<div class="sendui-standard-content">

<div class="settings">
{form $form_subscriber, 'sendui~subscribers:subscribersave', array('idsubscriber' => $idsubscriber, 'idsubscriber_list' => $idsubscriber_list)}

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'email'}</div>
    <p>{ctrl_control 'email'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'date_insert'}</div>
    <p>{ctrl_control 'date_insert'}</p>
</div>

<p><input name="_submit" id="jforms_sendui_form_subscriber_submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Enregistrer" type="submit"></p>

{/form}
</div>
</div>

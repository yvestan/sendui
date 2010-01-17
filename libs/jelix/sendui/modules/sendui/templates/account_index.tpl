<h2 class="mainpage account float-left">Votre compte</h2>

<div class="ui-corner-all ui-helper-clearfix float-right sendui-padding-toolbar">
    <div class="fg-toolbar fg-buttonset fg-buttonset-single ui-helper-clearfix">
        <button class="fg-button ui-state-default ui-state-active ui-priority-primary ui-corner-left">Vos informations</button>
        <button class="fg-button ui-state-default ui-priority-primary">Domaines autorisés</button>
        <button class="fg-button ui-state-default ui-priority-primary ui-corner-right">Crédits</button>
    </div>
</div>

<div class="spacer"></div>

<div class="sendui-standard-content">

<div class="settings">
{form $customer_settings, 'sendui~account:index'}

<div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
    <h3>Vos informations</h3>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'lastname'}</div>
    <p>{ctrl_control 'lastname'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'firstname'}</div>
    <p>{ctrl_control 'firstname'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'company'}</div>
    <p>{ctrl_control 'company'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'email'}</div>
    <p>{ctrl_control 'email'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'username'}</div>
    <p>{ctrl_control 'username'}</p>
</div>

<div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
    <h3>Votre adresse</h3>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'address'}</div>
    <p>{ctrl_control 'address'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'zip'}</div>
    <p>{ctrl_control 'zip'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'city'}</div>
    <p>{ctrl_control 'city'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'country'}</div>
    <p>{ctrl_control 'country'}</p>
</div>


<p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Enregistrer" type="submit"></p>

{/form}
</div>
</div>

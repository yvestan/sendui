{if empty($idsubscriber_list)}
    <h2 class="mainpage user-add">Ajouter un utilisateur</h2>
{else}
    <h2 class="mainpage account">Modifier un utilisateur</h2>
    <h3 class="sendui-mainpage-h3">{$customer->login}</h3>
{/if}

<div class="sendui-standard-content">

    <div class="settings">
    {form $form_customer, 'sendui~globaladmin:saveuser', array('idcustomer' => $idcustomer, 'from_page' => $from_page)}

    <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
        <h3>Informations générales</h3>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'login'}</div>
        <p>{ctrl_control 'login'}</p>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'password'}</div>
        <p>{ctrl_control 'password'}</p>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'email'}</div>
        <p>{ctrl_control 'email'}</p>
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

    <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
        <h3>Quotas d'expédition</h3>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'batch_quota'}</div>
        <p>{ctrl_control 'batch_quota'}</p>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'pause_quota'}</div>
        <p>{ctrl_control 'pause_quota'}</p>
    </div>

    <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
        <h3>Paramètres d'expedition</h3>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'return_path'}</div>
        <p>{ctrl_control 'return_path'}</p>
    </div>

    <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
        <h3>Adresse</h3>
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

    <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
        <h3>Interface</h3>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'theme'}</div>
        <p>{ctrl_control 'theme'}</p>
    </div>

    <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
        <h3>Status de l'utlisateur</h3>
    </div>

    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label 'active'} {ctrl_control 'active'}</div>
    </div>

    <p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Enregistrer" type="submit"></p>

    {/form}
    </div>

    {if !empty($idcustomer)}
    <p class="sendui-margin-top-double">
        <a href="{jurl 'sendui~globaladmin:userdelete', array('idcustomer' => $idcustomer)}" 
            class="confirm_action table-delete" title="Êtes-vous sur de vouloir supprimer cet utilisateur ? CETTE ACTION NE PEUT PAS ÊTRE ANNULÉE !">Supprimer cet utilisateur</a>
    </p>
    {/if}

</div>

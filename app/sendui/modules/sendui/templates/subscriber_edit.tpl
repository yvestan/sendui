<h2 class="mainpage subscribers">Modifier l'abonné {$subscriber->email}</h2>
{if !empty($idsubscriber_list)}
    <h3 class="sendui-mainpage-h3">
        <a href="{jurl 'sendui~subscribers:view', array('idsubscriber_list' => $idsubscriber_list)}" 
            class="table-go">{$subscriber_list->name}</a></h3>
{/if}

<div class="sendui-standard-content">

        <div class="settings" id="subscribers-unique">
        {form $form_subscriber, 'sendui~subscribers:subscribersave', array('idsubscriber' => $idsubscriber, 'idsubscriber_list' => $idsubscriber_list, 'from_page' => $from_page)}

        <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
            <h3>Identité</h3>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'email'}</div>
            <p>{ctrl_control 'email'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'fullname'}</div>
            <p>{ctrl_control 'fullname'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'firstname'}</div>
            <p>{ctrl_control 'firstname'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'lastname'}</div>
            <p>{ctrl_control 'lastname'}</p>
        </div>

        <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
            <h3>Adresse et téléphone</h3>
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
            <div>{ctrl_label 'country'}</div>
            <p>{ctrl_control 'country'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'phone'}</div>
            <p>{ctrl_control 'phone'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'mobile'}</div>
            <p>{ctrl_control 'mobile'}</p>
        </div>


        <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
            <h3>Informations sur l'abonnement</h3>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'status'}</div>
            <p>{ctrl_control 'status'}</p>
        </div>

        <div class="bloc-form ui-corner-all dateinput">
            <div>{ctrl_label 'confirmed'}</div>
            <p>{ctrl_control 'confirmed'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'html_format'}</div>
            <p>{ctrl_control 'html_format'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'text_format'}</div>
            <p>{ctrl_control 'text_format'}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{ctrl_label 'subscribe_from'}</div>
            <p>{ctrl_control 'subscribe_from'}</p>
        </div>

        <p class="sendui-margin-top">
            <input name="_submit" id="jforms_sendui_form_subscriber_submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Enregistrer" type="submit">
            <a href="{jurl $from_page, array('idsubscriber_list' => $idsubscriber_list, 'idsubscriber' => $idsubscriber)}" class="cancel cross">annuler</a>
        </p>

        {/form}
        </div>

        {if !empty($idsubscriber)}
        <p class="sendui-margin-top-double">
            <a href="{jurl 'sendui~subscribers:deletesubscriber', array('idsubscriber' => $idsubscriber, 'idsubscriber_list' => $idsubscriber_list, 'from_page' => 'sendui~subscriber:edit')}" 
                class="confirm_action flag-green" title="Êtes-vous sur de vouloir marquer cet abonné comme étant à supprimer ?">Marquer cette abonné comme &laquo; supprimé &raquo;</a>
             | <a href="{jurl 'sendui~subscribers:purgesubscriber', array('idsubscriber' => $idsubscriber, 'idsubscriber_list' => $idsubscriber_list, 'from_page' => 'sendui~subscribers:view')}" 
                class="confirm_action user-delete" title="Êtes-vous sur de vouloir supprimer cet abonné ? CETTE ACTION NE PEUT PAS ÊTRE ANNULÉE !">Supprimer <strong>définitivement</strong> cet abonné</a>
        </p>
        {/if}

</div>

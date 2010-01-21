<div class="float-left">
    <h2 class="mainpage subscribers">Abonnés {$subscriber->email}</h2>
    {if !empty($idsubscriber_list)}
        <h3 class="sendui-mainpage-h3">
            <a href="{jurl 'sendui~subscribers:view', array('idsubscriber_list' => $idsubscriber_list)}" 
                class="table-go">{$subscriber_list->name}</a></h3>
    {/if}
</div>

<div class="sendui-padding-mainpage float-right">
    <a href="{jurl 'sendui~subscriber:prepare', array('idsubscriber' => $subscriber->idsubscriber, 'idsubscriber_list' => $idsubscriber_list)}" 
        class="fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-pencil"></span>Modifier la fiche</a>
</div>

<div class="spacer"></div>

<div class="sendui-standard-content">

        <div class="settings" id="subscribers-unique">

        <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
            <h3>Identité</h3>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->email}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->fullname}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->firstname}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->lastname}</div>
        </div>

        <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
            <h3>Adresse et téléphone</h3>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->address}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->zip}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->country}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->phone}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->mobile}</div>
        </div>


        <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
            <h3>Informations sur l'abonnement</h3>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->status}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->confirmed}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->html_format}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->text_format}</div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div>{$subscriber->subscribe_from}</div>
        </div>

        </div>

        {if !empty($subscriber->idsubscriber)}
        <p class="sendui-margin-top-double">
            <a href="{jurl 'sendui~subscribers:listdelete', array('idsubscriber_list' => $subscriber_list->idsubscriber_list)}" 
                class="confirm_action user-delete" title="Êtes-vous sur de vouloir supprimer cet abonné ? CETTE ACTION NE PEUT PAS ÊTRE ANNULÉE !">Supprimer cet abonné</a>
        </p>
        {/if}

</div>

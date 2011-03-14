<div class="float-left">
    <h2 class="mainpage subscribers">Abonné {$subscriber->email}</h2>
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
            <div><span class="sendui-grey">Email :</span> 
                {if $subscriber->status==3}<del>{$subscriber->email}</del>{else}{$subscriber->email}{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Nom complet :</span> 
                {if !empty($subscriber->fullname)}{$subscriber->fullname}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Prénom :</span> 
                {if !empty($subscriber->firstname)}{$subscriber->firstname}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Nom :</span> 
                {if !empty($subscriber->lastname)}{$subscriber->lastname}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
            <h3>Adresse et téléphone</h3>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Adresse :</span> 
                {if !empty($subscriber->address)}{$subscriber->address}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Code postal :</span> 
                {if !empty($subscriber->zip)}{$subscriber->zip}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Ville :</span> 
                {if !empty($subscriber->city)}{$subscriber->city}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Pays :</span> 
                {if !empty($subscriber->country)}{$subscriber->country}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Téléphone :</span> 
                {if !empty($subscriber->phone)}{$subscriber->phone}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Mobile :</span> 
                {if !empty($subscriber->mobile)}{$subscriber->mobile}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        <div class="section-form sendui-bloc-simple ui-widget-header ui-corner-all">
            <h3>Informations sur l'abonnement</h3>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Status :</span> 
                {if $subscriber->status==0}<span class="flag-blue">inactif</span>{/if}
                {if $subscriber->status==1}<span class="flag-green">actif</span>{/if}
                {if $subscriber->status==2}<span class="flag-yellow">rebond (à supprimer/vérifier)</span>{/if}
                {if $subscriber->status==3}<span class="flag-red">supprimé (à confirmer)</span>{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Confirmé :</span> 
                {if !empty($subscriber->confirmed)}{$subscriber->confirmed}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Format HTML :</span> 
                {if !empty($subscriber->html_format)}accepté{else}non{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Format texte :</span> 
                {if !empty($subscriber->text_format)}accepté{else}non{/if}
            </div>
        </div>

        <div class="bloc-form ui-corner-all">
            <div><span class="sendui-grey">Inscrit depuis :</span> 
                {if !empty($subscriber->subscribe_from)}{$subscriber->subscribe_from}{else}<i>non précisé</i>{/if}
            </div>
        </div>

        </div>

        {if !empty($subscriber->idsubscriber)}
        <p class="sendui-margin-top-double">
            {if $subscriber->status!=3}
            <a href="{jurl 'sendui~subscribers:deletesubscriber', array('idsubscriber' => $subscriber->idsubscriber, 'idsubscriber_list' => $subscriber->idsubscriber_list, 'from_page' => 'sendui~subscriber:index')}" 
                class="confirm_action flag-green" title="Êtes-vous sur de vouloir marquer cet abonné comme étant à supprimer ?">Marquer cette abonné comme &laquo; supprimé &raquo;</a>
             | {/if}
             <a href="{jurl 'sendui~subscribers:purgesubscriber', array('idsubscriber' => $subscriber->idsubscriber, 'idsubscriber_list' => $subscriber->idsubscriber_list, 'from_page' => 'sendui~subscribers:view')}" 
                class="confirm_action user-delete" title="Êtes-vous sur de vouloir supprimer cet abonné ? CETTE ACTION NE PEUT PAS ÊTRE ANNULÉE !">Supprimer <strong>définitivement</strong> cet abonné</a>
        </p>
        {/if}


</div>

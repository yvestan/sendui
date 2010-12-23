{if empty($idbounce_config)}
    <h2 class="mainpage folder-bug">Ajouter une boîte de retour</h2>
{else}
    <h2 class="mainpage folder-bug">Gérer la boîte de retour</h2>
    <h3 class="sendui-mainpage-h3">{$bounce_config->name}</h3>
{/if}

<div class="sendui-standard-content">

<div class="settings">
{form $form_bounce_config, 'sendui~bouncescheck:save', array('idbounce_config' => $idbounce_config, 'from_page' => $from_page)}

    {formcontrols}
    <div class="bloc-form ui-corner-all">
        <div>{ctrl_label}</div>
        <p>{ctrl_control}</p>
    </div>
    {/formcontrols}

    <p class="sendui-margin-top">
        <input name="_submit" id="jforms_sendui_form_bounce_config_submit" 
            class="jforms-submit fg-button ui-state-default ui-corner-all" value="Enregistrer" type="submit">
        {if !empty($from_page)}
            <a href="{jurl $from_page, array('idbounce_config' => $idbounce_config)}" class="cancel cross">annuler</a>
        {/if}
    </p>

{/form}
</div>

{if !empty($idbounce_config)}
<p class="sendui-margin-top-double">
    <a href="{jurl 'sendui~subscribers:listdelete', array('idbounce_config' => $idbounce_config)}" 
        class="confirm_action table-delete" title="Êtes-vous sur de vouloir supprimer cette configuration ? CETTE ACTION NE PEUT PAS ÊTRE ANNULÉE !">Supprimer cette configuration</a>
</p>
{/if}

</div>

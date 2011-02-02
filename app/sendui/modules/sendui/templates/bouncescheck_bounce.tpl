<h2 class="mainpage folder-bug">Détails du rebond</h2>

<div class="sendui-standard-content">

    <div class="settings" id="subscribers-unique">

        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Email</div>
            <p><a href="mailto:{$bounce->email}">{$bounce->email}</a></p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Sévérité</div>
            <p>{$bounce->bounce_type} | <a href="{jurl 'sendui~bounceslist', array('bounce_type' => $bounce->bounce_type)}">voir tous les rebonds de ce type</a></p>
        </div>

        {if !empty($bounce->action)}
        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Résultat de l'expédition</div>
            <p>{$bounce->action}</p>
        </div>
        {/if}

        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Catégorie d'erreur</div>
            <p>{$bounce->rule_cat} | <a href="{jurl 'sendui~bounceslist', array('rule_cat' => $bounce->rule_cat)}">voir tous les rebonds de ce type</a></p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Numéro de la catégorie d'erreur</div>
            <p>{$bounce->rule_no} | <a href="{jurl 'sendui~bounceslist', array('rule_no' => $bounce->rule_no)}">voir tous les rebonds de ce type</a></p>
        </div>

        {if !empty($bounce->diag_code)}
        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Message d'erreur standard</div>
            <p><code>{$bounce->diag_code}</code></p>
        </div>
        {/if}

        {if !empty($bounce->status_code)}
        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Code d'erreur standard</div>
            <p>{$bounce->status_code}</p>
        </div>
        {/if}

        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Date de la réponse</div>
            <p>{$bounce->date}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Expéditeur de la réponse</div>
            <p>{$bounce->from}</p>
        </div>

        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Type d'analyse</div>
            <p>{$bounce->rule_type}</p>
        </div>

        {if !empty($bounce->dsn_report)}
        <div class="bloc-form ui-corner-all">
            <div class="sendui-grey">Message d'erreur complet</div>
            <p><code>{$bounce->dsn_report}</code></p>
        </div>
        {/if}

        <p class="sendui-margin-top-double">
            <a href="{jurl 'sendui~subscriber:delete', array('email' => $bounce->email)}" 
                class="confirm_action user-delete" title="Êtes-vous sur de vouloir supprimer cet email ?">Supprimer cet email des listes</a>
        </p>

       <div class="spacer"></div>
    </div>

   <div class="spacer"></div>

</div>

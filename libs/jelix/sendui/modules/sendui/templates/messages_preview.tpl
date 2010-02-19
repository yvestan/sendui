{if empty($from_page)}
    {$steps}
{/if}

<h2 class="mainpage newmessage float-left">Résumé du message</h2>

<div class="sendui-padding-mainpage float-right">
    {if $message->status==2}
        <div class="progression ui-corner-all">
            <div class="status">Envoyée à <span class="nb_send">0</span> sur <span class="total_subcriber">{$message->count_recipients}</span></div>
            <div id="pb1" class="progress"></div>
            <div class="control-send">
                <a href="{jurl 'sendui~send:stop', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:preview')}" class="control-pause">suspendre</a>
            </div>
            <div class="spacer"></div>
        </div>
    {/if}
    {if ($message->status==3 || $message->status==4 || $message->status==5)}
        <div class="sendui-center"><span class="big ui-corner-all">Envoyé à {$message->count_recipients} abonnés</span></div>
    {/if}
</div>

<div class="spacer"></div>

<div class="sendui-standard-content">

        <div class="ui-state-highlight ui-corner-all sendui-padding-simple sendui-margin-bottom"> 
            {if $message->status==1}
                <p><span class="ui-icon ui-icon-info sendui-icon-float"></span>Le message est en <span class="clock"><strong>attente de validation</strong></span>. 
                    Pour le modifier, vous devez <a href="{jurl 'sendui~send:cancel', array('idmessage' => $idmessage, 'from_page' => 'sendui~messages:preview')}" class="control-stop">annuler la demande</a></p>
            {elseif $message->status==2}
                <p><span class="ui-icon ui-icon-info sendui-icon-float"></span>Le message est en <span class="package-go"><strong>cours d'envoi</strong></span>. 
                    Pour le modifier, vous devez <a href="{jurl 'sendui~send:stop', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:preview')}" class="control-pause">suspendre l'envoi</a></p>
            {elseif $message->status==3}
                <p><span class="ui-icon ui-icon-info sendui-icon-float"></span>L'envoi message est actuellement <span class="clock-pause"><strong>suspendu</strong></span>. 
                    Vous pouvez <a href="{jurl 'sendui~send:start', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~messages:preview')}" class="package-go">reprendre l'envoi</a></p>
            {elseif $message->status==4}
                Le message expédié partiellement est en attente de validation
            {elseif $message->status==5}
                <p><span class="ui-icon ui-icon-info sendui-icon-float"></span>Ce message a déjà été <span class="tick"><strong>envoyé</strong></span>. 
                    Vous pouvez <a href="{jurl 'sendui~settings:prepare', array('idmessage' => $message->idmessage, 'reuse' => 1)}" class="package-go">le ré-utiliser</a> pour un nouveau message</p>
            {else}
                <p><span class="ui-icon ui-icon-info sendui-icon-float"></span>Ce message est un brouillon</p>
            {/if}
        </div>

        {if !empty($success)}
            <div class="ui-state-error ui-corner-all sendui-padding-simple sendui-margin-bottom"> 
                    <p><span class="ui-icon ui-icon-alert sendui-icon-float"></span> Le message de test a été envoyé à {$success} adresse(s)</p>
            </div>
        {/if}

    <div class="ui-widget-content ui-tabs ui-corner-all sendui-bloc-simple sendui-margin-bottom">

        <h3 class="ui-tabs ui-widget-header sendui-padding-simple ui-corner-top sendui-noborder">Sujet et expéditeur</h3>

        <ul class="tabstyle">
            <li><span class="label">Nom du message</span>
                {$message->name}</li>
            <li><span class="label">Sujet</span>
                {$message->subject}</li>
            <li><span class="label">Nom de l'expéditeur</span>
                {$message->from_name}</li>
            <li><span class="label">Email de l'expéditeur</span>
                {$message->from_email}</li>
            <li class="last"><span class="label">Adresse de réponse</span>
                {$message->reply_to}</li>
        </ul>

        {if ($message->status==0 || $message->status==4)}
        <div class="sendui-right sendui-padding-simple">
            <a href="{jurl 'sendui~settings:prepare', array('idmessage' => $idmessage, 'from_page' => 'sendui~messages:preview')}" 
                class="table-edit sendui-link-view">modifier ces informations</a>
        </div>
        {/if}

    </div>

    <div class="ui-widget-content ui-tabs ui-corner-all sendui-bloc-simple sendui-margin-bottom">

        <h3 class="ui-tabs ui-widget-header sendui-padding-simple ui-corner-top sendui-noborder">Message</h3>

        <ul class="tabstyle">
            {if !empty($message->html_message)}
                <li class="page-white-code sendui-icon">Vous avez créé un message au format HTML 
                    &nbsp;&nbsp;<a href="{jurl 'sendui~compose:preview', array('idmessage' => $idmessage, 'from_page' => 'sendui~messages:preview', 'type_msg' => 'html')}" class="eye">prévisualiser</a></li>
            {else}
                <li class="page-white-error sendui-icon">Vous n'avez pas créé de message au format HTML</li>
            {/if}
            {if !empty($message->text_message)}
                <li class="page-white last sendui-icon">Vous avez créé un message au format texte
                    &nbsp;&nbsp;<a href="{jurl 'sendui~compose:preview', array('idmessage' => $idmessage, 'from_page' => 'sendui~messages:preview', 'type_msg' => 'txt')}" class="eye">prévisualiser</a></li>
            {else}
                <li class="page-white-error last sendui-icon">Vous n'avez pas créé de message au format texte</li>
            {/if}
        </ul>

        {if ($message->status==0 || $message->status==4)}
        <div class="sendui-right sendui-padding-simple">
            <a href="{jurl 'sendui~compose:prepare', array('idmessage' => $idmessage, 'from_page' => 'sendui~messages:preview')}" 
                class="layout-edit sendui-link-view">modifier le message</a>
        </div>
        {/if}

    </div>

    <div class="ui-widget-content ui-tabs ui-corner-all sendui-bloc-simple sendui-margin-bottom">

        <h3 class="ui-tabs ui-widget-header sendui-padding-simple ui-corner-top sendui-noborder">Destinataires</h3>

        <ul class="tabstyle">
            <li><strong>{if $nb_subscribers==0}Aucun{else}{$nb_subscribers}{/if}</strong> destinataires pour ce message</li>
        </ul>

        {if ($message->status==0 || $message->status==4)}
        <div class="sendui-right sendui-padding-simple">
            <a href="{jurl 'sendui~recipients:index', array('idmessage' => $idmessage, 'from_page' => 'sendui~messages:preview')}" 
                class="book-edit sendui-link-view">changer les destinataires</a>
        </div>
        {/if}

    </div>

    <div class="ui-widget-content ui-tabs ui-corner-all sendui-bloc-simple sendui-margin-bottom">

        <h3 class="ui-tabs ui-widget-header sendui-padding-simple ui-corner-top sendui-noborder">Envoyer un message de test</h3>

        <form method="post" action="{jurl 'sendui~send:test', array('idmessage' => $idmessage, 'from_page' => 'sendui~messages:preview')}" id="send_test">
            <div class="sendui-padding-simple ui-corner-all">
                <label for="email_test">Adresse(s) email(s) de test</label>
                &nbsp;<input type="text" name="emails_test" id="emails_test" value="{$email_customer}" /> 
                <input name="_submit" id="email_test_submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Envoyer le test" type="submit" />
                <span class="legend_form">Maximum : 5 adresses emails séparées par des virgules.</span>
            </div>
        </form>

    </div>

    <div class="spacer">&nbsp;</div>


    <div class="sendui-margin-top">

        {if empty($message->html_message) && empty($message->text_message)}
            <div class="ui-state-highlight ui-corner-all sendui-padding-simple"> 
                <p><span class="ui-icon ui-icon-info sendui-icon-float"></span>Votre message est vide ! Vous devez créer une version HTML et/ou une version texte pour pouvoir l'envoyer.</p>
            </div>
        {/if}

        {if $nb_subscribers==0}
            <div class="ui-state-highlight ui-corner-all sendui-padding-simple"> 
                <p><span class="ui-icon ui-icon-info sendui-icon-float"></span>Votre message ne contient aucun destinataire ! Vous devez choisir une liste de destinataires pour pouvoir l'envoyer.</p>
            </div>
        {/if}

        {if empty($ok_to_send)}
            <div class="sendui-margin-top sendui-center">
                <a href="#" class="fg-button ui-state-default ui-state-disabled fg-button-icon-left ui-corner-all">
                    <span class="ui-icon ui-icon-circle-check"></span>Envoyer le message maintenant</a>
            </div>
        {else}
            <div class="sendui-center">
                <a href="{jurl 'sendui~send:index', array('idmessage' => $idmessage)}" class="fg-button ui-state-active fg-button-icon-left ui-corner-all sendui-big-button">
                    <span class="ui-icon ui-icon-circle-check"></span>Envoyer le message maintenant</a>
                <div class="spacer">&nbsp;</div>
            </div>
        {/if}
   
    </div>

  
</div>

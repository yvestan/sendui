<h2 class="mainpage newmessage">Résumé du message</h2>

<div class="sendui-standard-content">

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

        <div class="sendui-right sendui-padding-simple">
            <a href="{jurl 'sendui~settings:prepare', array('idmessage' => $idmessage, 'from_page' => 'sendui~messages:preview')}" class="table-edit sendui-link-view">modifier ces informations</a>
        </div>

    </div>

    <div class="ui-widget-content ui-tabs ui-corner-all sendui-bloc-simple sendui-margin-bottom">

        <h3 class="ui-tabs ui-widget-header sendui-padding-simple ui-corner-top sendui-noborder">Message</h3>

        <ul class="tabstyle">
            {if !empty($message->html_message)}
                <li class="page-white-code sendui-icon">Vous avez créé un message au format HTML</li>
            {else}
                <li class="page-white-error sendui-icon">Vous n'avez pas créé de message au format HTML</li>
            {/if}
            {if !empty($message->text_message)}
                <li class="page-white last sendui-icon">Vous avez créé un message au format texte</li>
            {else}
                <li class="page-white-error last sendui-icon">Vous n'avez pas créé de message au format texte</li>
            {/if}
        </ul>

        <div class="sendui-right sendui-padding-simple">
            <a href="{jurl 'sendui~compose:prepare', array('idmessage' => $idmessage, 'from_page' => 'sendui~messages:preview')}" class="layout-edit sendui-link-view">modifier le message</a>
        </div>

    </div>

    <div class="ui-widget-content ui-tabs ui-corner-all sendui-bloc-simple">

        <h3 class="ui-tabs ui-widget-header sendui-padding-simple ui-corner-top sendui-noborder">Destinataires</h3>

        <ul class="tabstyle">
            {foreach $message_subscriber_list as $subscriber_list}
                <li>{$subscriber_list->name} <span class="sendui-strong-grey">({$subscriber_subscriber_list->countSubscriberByList($subscriber_list->idsubscriber_list)} abonnés)</span></a>
            {/foreach}
        </ul>

        <div class="sendui-right sendui-padding-simple">
            <a href="{jurl 'sendui~recipients:index', array('idmessage' => $idmessage, 'from_page' => 'sendui~messages:preview')}" class="book-edit sendui-link-view">changer les destinataires</a>
        </div>

    </div>

    <div class="sendui-margin-top">
    {if empty($message->html_message) && empty($message->text_message)}

        <div class="ui-state-highlight ui-corner-all sendui-padding-simple"> 
            <p><span class="ui-icon ui-icon-info"></span>Votre message est vide ! Vous devez créer une version HTML et/ou une version texte pour pouvoir l'envoyer.</p>
        </div>

        <div class="sendui-margin-top">
            <a href="#" class="fg-button ui-state-default ui-state-disabled fg-button-icon-left ui-corner-all">
                <span class="ui-icon ui-icon-circle-check"></span>Envoyer le message maintenant</a>
        </div>

    {else}
            <a href="{jurl 'sendui~send:index', array('idmessage' => $idmessage)}" class="fg-button ui-state-default fg-button-icon-left ui-corner-all">
                <span class="ui-icon ui-icon-circle-check"></span>Envoyer le message maintenant</a>
    {/if}
    </div>

  
</div>

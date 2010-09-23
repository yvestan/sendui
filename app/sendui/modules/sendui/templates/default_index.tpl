<h2 class="main dashboard">Tableau de bord</h2>

{literal}
<script type="text/javascript">
    $('.sendui-float-bloc').masonry({ columnWidth: 200 });
    $('.sendui-float-bloc').masonry({ singleMode: true });
</script>
{/literal}

<div class="sendui-col-one">

    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all sendui-float-bloc">
        <h3 class="ui-widget-header ui-corner-all">
        <span class="current">Message(s) en cours d'envoi</span></h3>
        {if $nb_current_messages>0}
            <div class="sendui-padding-simple">

                {foreach $current_messages as $message}
                <div class="intro-send"><span class="sendui-grey">[{$message->sent_start|jdatetime:'db_datetime','lang_date'}]</span> 
                    <a href="{jurl 'sendui:messages:preview', array('idmessage' => $message->idmessage)}" class="sendui-italic layout-content">{$message->subject}</a>
                </div>	
                <div class="progression ui-corner-all">
                <div class="status">Envoyée à <span class="nb_send">0</span> sur <span class="total_subcriber">{$message->total_recipients}</span></div>
                    <div id="pb1" class="progress"></div>
                    <div class="control-send">
                        <a href="{jurl 'sendui~send:stop', array('idmessage' => $message->idmessage, 'from_page' => 'sendui~default:index')}" class="control-pause">arrêter</a>
                    </div>
                    <div class="spacer"></div>
                </div>
                {/foreach}

            </div>
        {else}
            <p class="no-result">Vous n'avez pas de message en cours d'envoi actuellement. 
                Souhaitez-vous <a href="{jurl 'sendui~settings:prepare'}" class="newmessage">envoyer un message</a> ?</p>
        {/if}

    </div>

    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all sendui-float-bloc">
        <h3 class="ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
        <span class="last-message">Dernier message envoyé</span></h3>

        {if !empty($last_message)}
        <div class="sendui-padding-simple">

            <div class="sujet">[{$last_message->name}] <em>{$last_message->subject}</em></div>
            <div class="sendui-detail-simple">Envoyé le {$last_message->sent_start|jdatetime:'db_datetime','lang_date'} à {$last_message->count_recipients} abonnés</div>

            <div class="sendui-margin-top-simple">
                <!--<a href="{jurl 'sendui~messages:preview', array('idmessage' => $last_message->idmessage)}" class="chart-pie">statistiques</a> | -->
                <a href="{jurl 'sendui~messages:preview', array('idmessage' => $last_message->idmessage)}" class="layout-content">message</a>
            </div>

        </div>
        {else}
            <p class="no-result">Vous n'avez pas encore envoyé de message. 
                Souhaitez-vous <a href="{jurl 'sendui~settings:prepare'}" class="newmessage">créer un nouveau message</a> ?</p>
        {/if}
    </div>

    
</div>

<div class="sendui-col-two">

    <!--<div class="ui-tabs ui-widget ui-widget-content ui-corner-all sendui-float-bloc">
        <h3 class="ui-widget-header ui-corner-all">
        <span class="schedule">Message(s) programmé(s)</span></h3>
        <p class="no-result">Vous n'avez pas programmé d'envoi de message. Souhaitez-vous <a href="{jurl 'sendui~settings:index'}" class="time-add">programmer un envoi</a> ?</p>
    </div>-->

    <!--<div class="ui-tabs ui-widget ui-widget-content ui-corner-all sendui-float-bloc">
        <h3 class="ui-widget-header ui-corner-all">
        <span class="credits">Crédit(s) disponible(s)</span></h3>
        {if !empty($credits)}
            <div class="content-bloc sendui-padding-simple">
                <div class="sendui-center"><span class="big ui-corner-all">{$credits} crédit(s)</span> <span class="float-left">disponible(s) sur votre compte</a></span></div>
                <div class="spacer"></div>
                <div class="sendui-margin-top-simple"><a href="{jurl 'sendui~account:credits'}" class="basket-add">obtenir d'autres crédits</a></div>
            </div>
        {else}
            <p class="no-result">Vos n'avez aucun crédit actuellement sur votre compte. Souhaitez-vous <a href="{jurl 'sendui~account:credits'}" class="basket-add">créditer votre compte</a> ?</p>
        {/if}
    </div>-->

</div>


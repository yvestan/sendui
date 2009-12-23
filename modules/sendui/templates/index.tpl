<h2 class="main dashboard">Tableau de bord</h2>

<div class="ui-tabs ui-widget ui-widget-content ui-corner-all sendui-float-bloc">
    <h3 class="ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
    <span class="last-message">Dernier message envoyé</span></h3>
    <div class="">
        <div class="sujet">Machin vous porrpose de venir voir un truc</div>
        Envoyé le 12 décembre 2009 à 23000 abonnés
    </div>
    <p class="no-result">Vous n'avez pas encore envoyé de message. Souhaitez-vous <a href="{jurl 'sendui~settings:index'}">créer un nouveau message</a> ?</p>
</div>

<div class="ui-tabs ui-widget ui-widget-content ui-corner-all sendui-float-bloc">
    <h3 class="ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
    <span class="current">Message(s) en cours d'envoi</span></h3>
    <p class="no-result">Vous n'avez pas de message en cours d'envoi actuellement. Souhaitez-vous <a href="{jurl 'sendui~settings:index'}">envoyer un message</a> ?</p>
</div>

<div class="ui-tabs ui-widget ui-widget-content ui-corner-all sendui-float-bloc">
    <h3 class="ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
    <span class="schedule">Message(s) programmé(s)</span></h3>
    <p class="no-result">Vous n'avez pas programmé d'envoi de message. Souhaitez-vous <a href="{jurl 'sendui~settings:index'}">programmer un envoi</a> ?</p>
</div>

<div class="ui-tabs ui-widget ui-widget-content ui-corner-all sendui-float-bloc">
    <h3 class="ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
    <span class="credits">Crédit(s) disponible(s)</span></h3>
    {if !empty($credits)}
        <div class="content-bloc">
            <span class=" ui-corner-all">{$credits} crédit(s)</span> disponible(s)
            <a href="#">obtenir d'autres crédits</a>
        </div>
    {else}
    <p class="no-result">Vos n'avez aucun crédit actuellement sur votre compte. Souhaitez-vous <a href="{jurl 'sendui~account:credits'}">créditer votre compte</a> ?</p>
    {/if}
</div>


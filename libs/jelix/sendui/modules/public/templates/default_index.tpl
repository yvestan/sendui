<h2 class="sendui_h2">Gestion de votre abonnement</h2>
{jurl 'public~default:subscribe'}

{if !empty($sendui_token)}
<ul>
    <li><a href="{jurl 'sendui~default:subscribeform'}">Inscription</a></li>
    <li><a href="{jurl 'sendui~default:unsubscribeform'}">Desinscription</a></li>
</ul>
{else}
    <div class="sendui_error">
        Vous devez gérer votre abonnement depuis le site
        sur lequel vous vous êtes inscrit
    </div>
{/if}

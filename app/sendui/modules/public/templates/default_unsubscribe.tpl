<h2 class="sendui_h2">Désincription</h2>

{if !empty($token_incorrect)}
    <div class="sendui_error">
        Le lien de désinscription n'est pas valide ou vous n'êtes plus inscrit à cette liste.
        <a href="javascript:history.back()">retour à la page précédente</a>
    </div>
{/if}

{if !empty($unsubscribe_error)} 
    <div class="sendui_error">
        Il y a eu un problème pendant le processus de désincription. Veuillez contacter le webmaster.
        <a href="javascript:history.back()">retour à la page précédente</a>
    </div>
{/if}

{if !empty($response_unsubscribe)} 
    <div class="sendui_success">
        Vous êtes désabonné de la liste <em>{$subscriber_infos->name}</em>
        <a href="javascript:history.back(-2)">retour à la page précédente</a>
    </div>
{/if}

{if !empty($subscriber_token)} 
    <div class="sendui_success">
        Pour supprimer l'abonnement de <em>{$subscriber_infos->email}</em> à la liste 
        <em>{$subscriber_infos->name}</em>, veuillez cliquer sur le lien suivant :
        <br /><br /><a href="{jurl 'public~default:unsubscribe', array('t' => $subscriber_token, 'us' => true)}">me désabonner de la liste <em>{$subscriber_infos->name}</em></a>
    </div>
{/if}

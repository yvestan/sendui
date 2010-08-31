<h2 class="sendui_h2">Inscription</h2>

{if !empty($formulaire_incorrect)}
    <div class="sendui_error">
        Le formulaire que vous avez rempli n'est pas valide, Votre inscription n'a pas pu aboutir.<br />
        <a href="#">retour à la page précédente</a>
    </div>
{/if}

{if !empty($email_false)} 
    <div class="sendui_error">
        Vous n'avez pas correctement rempli votre adresse email.<br />
        <a href="#">retour à la page précédente</a>
    </div>
{/if}

{if !empty($email_exist)} 
    <div class="sendui_error">
        Vous êtes déjà inscrit à cette liste.<br />
        <a href="#">retour à la page précédente</a>
    </div>
{/if}

{if !empty($response_subscribe)} 
    <div class="sendui_success">
        Votre inscription a bien été prise en compte.<br />
        <a href="#">retour à la page précédente</a>
    </div>
{/if}

{if !empty($error_subscribe)} 
    <div class="sendui_error">
        Il y a eu un problème pendant votre inscription. Merci de réessayer plus tard.<br />
        <a href="#">retour à la page précédente</a>
    </div>
{/if}

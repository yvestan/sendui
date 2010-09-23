<div class="ui-widget ui-widget-content ui-corner-all sendui-center-bloc sendui-padding-simple">

    {if ! $isLogged}

        {if $failed}
            <div class="error-message ui-corner-all ui-state-error sendui-padding-simple">
                <span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span> 
                {@jauth~auth.failedToLogin@}</div>
        {else}
            <div class="sendui-bloc-simple ui-widget-header ui-corner-all">
                <h3>grafactory.net | connectez-vous</h3>
            </div>
        {/if}

    <form action="{formurl 'jauth~login:in'}" method="post" id="loginForm" class="form-login">
        <fieldset>

            <div><label for="login">Nom d'utilisateur</label></div>
            <p class="field"><input type="text" name="login" id="login" size="9" value="{$login}" /></p>

            <div><label for="password">Mot de passe</label></div>
            <p class="field"><input type="password" name="password" id="password" size="9" /></p>

            {if $showRememberMe}
            <div class="normal"><label for="rememberMe"><input type="checkbox" name="rememberMe" id="rememberMe" value="1" />
                se souvenir de moi sur cet ordinateur</label></div>
            <div class="spacer">&nbsp;</div>
            {/if}

            <p class="button-login">
                {formurlparam 'jauth~login:in'}

                {if !empty($auth_url_return)}
                    <input type="hidden" name="auth_url_return" value="{$auth_url_return|eschtml}" />
                {/if}

                <input name="submit" class="connexion-button fg-button ui-state-default ui-corner-all" value="Connexion" type="submit">
            </p>

        </fieldset>
    </form>
    {else}
        <p>{$user->login}</p>
    {/if}

</div>

<div id="navigation">
    <ul>
        <li id="deb">Vous êtes ici : </li>
        <li id="start"><a href="{jurl 'sendui~default:index'}">Accueil</a></li>
        {if isset($valuesNavigation)}
            {foreach $valuesNavigation as $url}
                {if $url['action']=='0'}
                    <li>{$url['title']}</li>
                {else}
                    <li><a href="{jurl $url['action'], $url['params']}">{$url['title']}</a></li>
                {/if}
            {/foreach}
        {/if}
    </ul>
</div>

<div id="global">

    <div id="header" class="ui-widget ui-widget-content ui-corner-all">

        <h1 id="logo">
            <a href="{jurl 'sendui~default:index'}">grafactory.net</a>
        </h1>

        <div id="account_menu" class="ui-widget-header ui-corner-bl sendui-menu-simple">
            <ul>
                <li><a href="{jurl 'sendui~account:index'}"><i>{$session->login}</i></a></li>
                <li><a href="{jurl 'sendui~account:index'}" class="account">votre compte</a></li>
                <!--<li><a href="{jurl 'sendui~account:credits'}" class="credits">vos crédits</a></li>-->
                <li><a href="{jurl 'sendui~login:out'}" class="logout">quitter</a></li>
            </ul>
            <div class="spacer"></div>
        </div>

        <div class="spacer"></div>
  
    </div>

    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all">

        <div id="main_menu">
            <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
                {foreach $menu_items as $k=>$m}
                    {if (!empty($active_page) && $active_page==$k)}
                        <li class="ui-corner-top ui-state-active {$k}"><a href="{jurl $m['url']}">{$m['name']}</a></li>
                    {else}
                        <li class="ui-corner-top ui-state-{$m['state']} {$k}"><a href="{jurl $m['url']}">{$m['name']}</a></li>
                    {/if}
                {/foreach}
            </ul>
        </div>

        {if !empty($navigation)}
        <div id="navigation">
            <ul>
                <li id="start"><a href="{jurl 'sendui~default:index'}">Accueil</a></li>
                {foreach $navigation as $url}
                    {if $url['action']=='0'}
                        <li class="bullet-go">{$url['title']}</li>
                    {else}
                        <li class="bullet-go"><a href="{jurl $url['action'], $url['params']}">{$url['title']}</a></li>
                    {/if}
                {/foreach}
            </ul>
            <div class="spacer">&nbsp;</div>
        </div>
        {/if}

        {$MAIN}

        <div class="spacer">&nbsp;</div>

    </div>

    <div id="footer" class="ui-widget ui-widget-content ui-corner-all">
        <ul>
            <li><a href="{jurl 'sendui~help:copyright'}" class="lightbulb_off">copyright/licence</a></li>
            <li><a href="{jurl 'sendui~help:index'}" class="information">aide</a></li>
            <li style="float:right;">Yves Tannier [<a href="http://www.grafactory.net">grafactory.net</a>]</li>
        </ul>
        <div class="spacer"></div>
    </div>

</div>

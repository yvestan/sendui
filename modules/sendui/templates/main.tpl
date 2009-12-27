<div id="global">

    <div id="header" class="ui-widget ui-widget-content ui-corner-all">

        <h1 id="logo">
            <a href="{jurl 'sendui~default:index'}">grafactory.net</a>
        </h1>

        <div id="account_menu" class="ui-widget-header ui-corner-bl sendui-menu-simple">
            <ul>
                <li><a href="{jurl 'sendui~account:index'}"><i>{$session->login}</i></a></li>
                <li><a href="{jurl 'sendui~account:index'}" class="account">votre compte</a></li>
                <li><a href="{jurl 'sendui~account:credits'}" class="credits">vos crédits</a></li>
                <li><a href="{jurl 'sendui~login:out'}" class="logout">quitter</a></li>
            </ul>
            <div class="spacer"></div>
        </div>

        <div class="spacer"></div>
  
    </div>

    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all" id="main_menu">
        <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <li class="ui-corner-top ui-state-default dashboard"><a href="{jurl 'sendui~default:index'}">tableau de bord</a></li>
            <li class="ui-corner-top ui-state-default newmessage"><a href="{jurl 'sendui~settings:prepare'}">créer &amp; envoyer un message</a></li>
            <li class="ui-corner-top ui-state-default drafts"><a href="{jurl 'sendui~messages:drafts'}">brouillons</a></li>
            <li class="ui-corner-top ui-state-default subscribers"><a href="{jurl 'sendui~subscribers:index'}">gérer vos listes d'abonnés</a></li>
            <li class="ui-corner-top ui-state-default archives"><a href="{jurl 'sendui~messages:sent'}">messages envoyés</a></li>
        </ul>

    {$MAIN}

    <div class="spacer">&nbsp;</div>

    </div>

    <div id="footer" class="ui-widget ui-widget-content ui-corner-all">
        <ul>
            <li><a href="{jurl 'sendui~help:copyright'}">copyright</a></li>
            <li><a href="{jurl 'sendui~help:index'}">aide</a></li>
            <li><a href="{jurl 'sendui~help:contact'}">contact</a></li>
        </ul>
        <div class="spacer"></div>
    </div>

</div>

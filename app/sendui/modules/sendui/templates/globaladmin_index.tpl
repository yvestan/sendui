<h2 class="main dashboard">Administration générale</h2>

<div class="sendui-standard-content">

    <div class="ui-state-error ui-corner-all sendui-padding-simple sendui-margin-bottom"> 
        <p><span class="ui-icon ui-icon-alert sendui-icon-float"></span> Seul l'administrateur <a href="{jurl 'sendui~account:index'}"><em>{$session->login}</em></a> a accès à cette page</p>
    </div>

    <p><a href="{jurl 'sendui~globaladmin:prepareuser'}" class="user-add">ajouter un utilisateur</a>&nbsp;|&nbsp;<a href="{jurl 'sendui~globaladmin:userlist'}" class="table">liste des utilisateurs</a></p>

</div>



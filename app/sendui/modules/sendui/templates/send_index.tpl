<h2 class="mainpage newmessage">Votre message est dans la file d'attente !</h2>

{literal}
<script type="text/javascript">
    $(document).ready(function() {
        //checkProcess();
    });
</script>
{/literal}
<div class="sendui-standard-content">

    <div class="ui-state-highlight ui-corner-all sendui-padding-simple"> 
        <p><span class="ui-icon ui-icon-circle-check sendui-icon-float"></span>
    Votre message a été placé dans la file d'attente. Vous recevrez un mail dès que l'envoi aura commencé.</p>
        <p>Afin d'éviter tout courrier frauduleux, tous les messages sont controlés par un opérateur.</p>
    </div>


    <div class="sendui-margin-top">
        <a href="{jurl 'sendui~default:index'}" class="dashboard">retourner au tableau du bord</a>
    </div>

    <ul>
        <li><a href="{jurl 'sendui~send:start', array('idmessage' => $idmessage)}">start</a></li>
        <li><a href="{jurl 'sendui~send:stop', array('idmessage' => $idmessage)}">stop</a></li>
    </ul>

<!-- Progressbar -->
<h2 class="demoHeaders">Progression de l'envoi</h2>	
<h3>Nombre d'email expédié <span class="nb_send">0</span>/<span class="total_subcriber">{$nb_subscribers}</span></h3>
<div id="pb1"></div>

</div>

{if empty($from_page)}
    {$steps}
{/if}

<h2 class="mainpage newmessage float-left">Définition de l'expéditeur et du sujet</h2>

<div class="sendui-padding-mainpage float-right">
    <a href="{jurl 'sendui~messages:sent', array('response' => 'ajax')}" class="open_dialog fg-button ui-state-active fg-button-icon-left ui-corner-all">
        <span class="ui-icon ui-icon-circle-plus"></span>Créer à partir d'un ancien message</a>
</div>

{literal}
<script type="text/javascript">
    $(function() {
        $('.open_dialog').click(function() {
            $(this).addClass('ui-state-active');
            $.ajax({
                type: "POST",
                url: $(this).attr('href'),
                success: function(msg){
                    $('#list-message').html(msg);
                    $('#list-message').slideDown('slow');
                }
            });
            return false;
        });
    });
</script>
{/literal}

<div class="spacer"></div>

<div class="sendui-standard-content">

<div id="list-message" style="display:none;"></div>

<div class="settings">
{form $message_settings, 'sendui~settings:save', array('idmessage' => $idmessage, 'from_page' => $from_page, 'reuse' => $reuse)}

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'name'}</div>
    <p>{ctrl_control 'name'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'subject'}</div>
    <p>{ctrl_control 'subject'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'from_name'}</div>
    <p>{ctrl_control 'from_name'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'from_email'}</div>
    <p>{ctrl_control 'from_email'}</p>
</div>

<div class="bloc-form ui-corner-all">
    <div>{ctrl_label 'reply_to'}</div>
    <p>{ctrl_control 'reply_to'}</p>
</div>

<p><input name="_submit" id="jforms_sendui_message_settings__submit" class="jforms-submit fg-button ui-state-default ui-corner-all" value="Continuer" type="submit" /></p>

{/form}
</div>
</div>

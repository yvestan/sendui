<div id="steps">
    {foreach $steps_description as $s}
    <div class="sendui-float-step">
        <div><span class="ui-widget-header ui-state-{$s['status']} ui-corner-all sendui-padding-simple">{$s['num']}</span></div>
        <div class="step-title ui-state{$s['status_text']}">
            {$s['name']}
        </div>
    </div>
    {/foreach}
    <div class="spacer"></div>
</div>

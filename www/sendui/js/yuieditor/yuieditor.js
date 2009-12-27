/*YAHOO.util.Event.on(window, 'load', function(e) {
 
    var myEditor = new YAHOO.widget.Editor('full_editor', {
        height: '200px',
        width: '505px',
        dompath: false,
        animate: true,
    });
 
    yuiImgUploader(myEditor, '[JURL A SAISIR]','image');
 
    myEditor.render();
 
    // Make sure the HTML is placed in the textarea element
    YAHOO.util.Event.on('submit_form', 'click', function(e) {
        myEditor.saveHTML();
    });
 
});*/
(function() {
    //Setup some private variables
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event;

        //The SimpleEditor config
        var myConfig = {
            height: '300px',
            width: '600px',
            dompath: true,
            focusAtStart: true
        };

    //Now let's load the SimpleEditor..
    var myEditor = new YAHOO.widget.Editor('submit_form', myConfig);
    myEditor.render();
})();


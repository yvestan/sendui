$(document).ready(function() {

    // confirmer avant de supprimer
    $(".confirm_action").live('click', function() {
        ok = confirm($(this).attr('title'));
        if(ok) {
            document.location.replace($(this).attr("href"));
        }
        return false; // toujours sinon double soumission href + onclick
    }); 

    // hover sur le menu
    $('#main_menu ul li').hover(
        function () {
            $(this).removeClass('ui-state-default').addClass('ui-state-hover');
        }, 
        function () {
            $(this).removeClass('ui-state-hover').addClass('ui-state-default');
        }
    );

    // ouvrir une page dans une iframe de fenêtre modal
    $('.open_frame').click(function(e) {
        e.preventDefault();
        var $this = $(this);
        var horizontalPadding = 30;
        var verticalPadding = 30;
        $('<iframe id="externalSite" class="externalSite" src="' + this.href + '" />').dialog({
            title: ($this.attr('title')) ? $this.attr('title') : 'Preview',
            autoOpen: true,
            width: 800,
            height: 500,
            modal: true,
            resizable: true,
            autoResize: true,
            overlay: {
                opacity: 0.5,
            }
        }).width(800 - horizontalPadding).height(500 - verticalPadding);            
    });

});

$(document).ready(function() {
    // confirmer avant de supprimer
    $(".confirm_action").click(function() {
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

});

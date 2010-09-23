$(document).ready(function() {
    $("#pb1").progressBar(0, {
            boxImage: base_path+'js/progressbar/images/progressbar.gif',
            barImage: {
                0:  base_path+'js/progressbar/images/progressbg_red.gif',
                30: base_path+'js/progressbar/images/progressbg_orange.gif',
                70: base_path+'js/progressbar/images/progressbg_green.gif'
            }
    });
    percent = 0;
    function checkProcess() {
        nextProcessStatus();
    }
    function getProcessStatus() {
        $.getJSON(link_status,
        function(msg) {

            $('.nb_send').html(msg.counter);

            try { clearTimeout(to); } catch (e) {}

            // calcul du pourcentage
            var percentage = Math.floor(100 * parseInt(msg.counter) / parseInt(nb_subscribers));

            // ajout dans la liste
            //$('#end').after('<li>'+msg.data+' '+percentage+'</li>');

            // si le counter récupéré est inférieur au nb total de subscriber, alors execution
            if(msg.counter<nb_subscribers) {

                // continue
                to = setTimeout(function(){nextProcessStatus()}, 3000);

                // avancement de la barre de progression
                $("#pb1").progressBar(percentage);

            } else {
                //$('#end').after('<li>Envoi terminé</li>');
                $("#pb1").progressBar(100);
            }
        });
    } 
    function nextProcessStatus(){getProcessStatus();}
    checkProcess();
});

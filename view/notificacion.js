$(document).ready(function(){

    mostrar_notificacion();

});

function mostrar_notificacion(){

    $.notify({
        icon:'glyphicon glyphicon-star',
        message: "Tiene una nueva respuesta de ticket Nro : ##", 
        url: "http://sistema-de-tickets.com"
    });
}

setInterval(function(){
    mostrar_notificacion();
}, 5000);

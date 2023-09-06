function init(){

}

$(document).ready(function() {

});
/* Cambiar de soporte a usuario y viceversa */
$(document).on("click", "#btnsoporte", function() {

    if($('#rol_id').val()==1){
        $('#lbltitulo').html("Acceso Soporte");
        $('#btnsoporte').html("Acceso Usuario");
        $('#rol_id').val(2);
        $('#imgtipo').attr("src","public/img/2.png")
    }else{
        $('#lbltitulo').html("Acceso Usuario");
        $('#btnsoporte').html("Acceso Soporte");
        $('#rol_id').val(1);
        $('#imgtipo').attr("src","public/img/1.png")
    }
    
});

init();
var tabla;
var usu_id =  $('#user_idx').val();
var rol_id =  $('#rol_idx').val();

function init(){
    $("#ticket_form").on("submit",function(e){
        guardar(e);
    });
}
/* Inicializar nuestro data Table */
$(document).ready(function(){

    $.post("../../controller/usuario.php?op=combo", function(data) {
        $('#usu_asig').html(data);
    })

    if (rol_id==1){
        tabla=$('#ticket_data').dataTable({
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            "searching": true,
            lengthChange: false,
            colReorder: true,
            buttons: [		          
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                    ],
            "ajax":{
                url: '../../controller/ticket.php?op=listar_x_usu',
                type : "post",
                dataType : "json",	
                data:{ usu_id : usu_id },						
                error: function(e){
                    console.log(e.responseText);	
                }
            },
            "bDestroy": true,
            "responsive": true,
            "bInfo":true,
            "iDisplayLength": 10,
            "autoWidth": false,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }     
        }).DataTable(); 
    }else{
        tabla=$('#ticket_data').dataTable({
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            "searching": true,
            lengthChange: false,
            colReorder: true,
            buttons: [		          
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                    ],
            "ajax":{
                url: '../../controller/ticket.php?op=listar',
                type : "post",
                dataType : "json",						
                error: function(e){
                    console.log(e.responseText);	
                }
            },
            "bDestroy": true,
            "responsive": true,
            "bInfo":true,
            "iDisplayLength": 10,
            "autoWidth": false,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }     
        }).DataTable(); 
    }

});

function ver(tick_id){
    window.open('http://localhost:80/Proyecto Sistema de Tickets/view/DetalleTicket/?ID='+ tick_id +'','_self');
}

function asignar(tick_id){
    $.post("../../controller/ticket.php?op=mostrar", {tick_id : tick_id}, function(data) {
        data = JSON.parse(data);
        $('#tick_id').val(data.tick_id);

        $('#mdltitulo').html('Asignar Agente');
        $("#modalasignar").modal('show');
    });
    
}

function guardar(e){
    e.preventDefault(); /* Para evitar doble click */
	var formData = new FormData($("#ticket_form")[0]);
    $.ajax({
        url: "../../controller/ticket.php?op=asignar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos){    
            var tick_id = $('#tick_id').val();
            $.post("../../controller/email.php?op=ticket_asignado", {tick_id : tick_id}, function (data) {

            }); /* Funcion para enviar correo de ticket asignado */
            swal("¡Correcto!", "Asignado Correctamente", "success");
            $("#modalasignar").modal('hide'); /* Ocultar el modal*/
            $('#ticket_data').DataTable().ajax.reload(); /* Recargar el formulario */
        }
    }); 
}

function CambiarEstado(tick_id){
    swal({
        title: "¡Advertencia!",
        text: "¿Esta seguro de reabrir el Ticket?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Si",
        cancelButtonText: "No",
        closeOnConfirm: false
    },
    function(isConfirm) {
        if (isConfirm) {
            $.post("../../controller/ticket.php?op=reabrir", {tick_id : tick_id, usu_id : usu_id}, function (data) {

            }); 

            $('#ticket_data').DataTable().ajax.reload();	

            swal({
                title: "¡Correcto!",
                text: "Ticket Abierto.",
                type: "success",
                confirmButtonClass: "btn-success"
            });
        }
    });
}

init();
function init(){

}

$(document).ready(function(){
    var usu_id = $('#user_idx').val();

    /* Si es un usuario, devuelve solo los tickets que le corresponden */
    if ($('#rol_idx').val() == 1){
        $.post("../../controller/usuario.php?op=total", { usu_id : usu_id }, function (data) {
            data = JSON.parse(data);
            $('#lbltotal').html(data.TOTAL);
        });

        $.post("../../controller/usuario.php?op=totalabierto", { usu_id : usu_id }, function (data) {
            data = JSON.parse(data);
            $('#lbltotalabiertos').html(data.TOTAL);
        });

        $.post("../../controller/usuario.php?op=totalcerrado", { usu_id : usu_id }, function (data) {
            data = JSON.parse(data);
            $('#lbltotalcerrados').html(data.TOTAL);
        });
        /* Gráfico con tickets divididos por categoria */
        $.post("../../controller/usuario.php?op=grafico", {usu_id:usu_id},function (data) {
            data = JSON.parse(data);
    
            new Morris.Bar({
                element: 'divgrafico',
                data: data,
                xkey: 'nom',
                ykeys: ['total'],
                labels: ['Value'],
                barColors: ["#1AB244"], 
            });
        }); 

        $('#idcalendar').fullCalendar({
            lang:'es',
            header:{
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'  
            },
            defaultView:'month',
            events:{
                url:'../../controller/ticket.php?op=usu_calendar',
                method:'POST',
                data:{usu_id:usu_id}
            }
        });
        
    /* Si es soporte, devuelve todos los tickets en el sistema */
    } else {
        $.post("../../controller/ticket.php?op=total", function (data) {
            data = JSON.parse(data);
            $('#lbltotal').html(data.TOTAL);
        });

        $.post("../../controller/ticket.php?op=totalabierto", function (data) {
            data = JSON.parse(data);
            $('#lbltotalabiertos').html(data.TOTAL);
        });

        $.post("../../controller/ticket.php?op=totalcerrado", function (data) {
            data = JSON.parse(data);
            $('#lbltotalcerrados').html(data.TOTAL);
        });
        /* Gráfico con tickets divididos por categoria */
        $.post("../../controller/ticket.php?op=grafico",function (data) {
            data = JSON.parse(data);
    
            new Morris.Bar({
                element: 'divgrafico',
                data: data,
                xkey: 'nom',
                ykeys: ['total'],
                labels: ['Value']
            });
        }); 

        $('#idcalendar').fullCalendar({
            lang:'es',
            header:{
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'  
            },
            defaultView:'month',
            events:{
                url:'../../controller/ticket.php?op=all_calendar'
            }
        });
    }


    
});

init();
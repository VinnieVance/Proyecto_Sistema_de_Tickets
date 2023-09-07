<?php
    require_once("../config/conexion.php");
    require_once("../models/Ticket.php");
    $ticket = new Ticket();
    
    require_once("../models/Usuario.php");
    $usuario = new Usuario();

    require_once("../models/Documento.php");
    $documento = new Documento();
    //insertar datos del ticket en la tabla tm_ticket
    switch($_GET["op"]){

        case "insert":
            $datos=$ticket->insert_ticket($_POST["usu_id"],$_POST["cat_id"],$_POST["cats_id"],$_POST["tick_titulo"],$_POST["tick_descrip"],$_POST["prio_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach ($datos as $row){
                    $output["tick_id"] = $row["tick_id"];

                    if (empty($_FILES['files']['name'])){

                    }else{
                        $countfiles = count($_FILES['files']['name']);
                        $ruta = "../public/document/".$output["tick_id"]."/";
                        $files_arr = array();

                        if(!file_exists($ruta)){
                            mkdir($ruta, 0777, true);
                        }

                        for($index = 0; $index < $countfiles; $index++){
                            $doc1 = $_FILES['files']['tmp_name'][$index];
                            $destino = $ruta.$_FILES['files']['name'][$index];

                            $documento->insert_documento($output["tick_id"], $_FILES['files']['name'][$index]);

                            move_uploaded_file($doc1, $destino);
                        }
                    }
                }
            }
            echo json_encode($datos);
        break;

        case "update":
            $ticket->update_ticket($_POST["tick_id"]);
            $ticket->insert_ticketdetalle_cerrar($_POST["tick_id"],$_POST["usu_id"]);
        break;

        case "reabrir":
            $ticket->reabrir_ticket($_POST["tick_id"]);
            $ticket->insert_ticketdetalle_reabrir($_POST["tick_id"],$_POST["usu_id"]);
        break;

        case "asignar":
            $ticket->update_ticket_asignacion($_POST["tick_id"],$_POST["usu_asig"]);
        break;

        case "listar_x_usu":
            $datos=$ticket->listar_ticket_x_usu($_POST["usu_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["tick_id"];
                $sub_array[] = $row["cat_nom"];
                $sub_array[] = $row["tick_titulo"];
                $sub_array[] = $row["prio_nom"];

                if($row["tick_estado"]=='Abierto'){
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }else{
                    $sub_array[] = '<a onClick="CambiarEstado('.$row["tick_id"].')"><span class="label label-pill label-danger">Cerrado</span></a>';
                }

                $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_crea"]));

                if($row["fech_asig"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin asignar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_asig"]));
                }

                if($row["fech_cierre"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin cerrar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_cierre"]));
                }

                if($row["usu_asig"]==null){
                    $sub_array[] = '<span class="label label-pill label-warning">Sin asignar</span>';
                }else{
                    $datos1 = $usuario->get_usuario_x_id($row["usu_asig"]);
                    foreach($datos1  as $row1){
                        $sub_array[] = '<span class="label label-pill label-success">'. $row1["usu_nom"].'</span>';
                    }
                }

                $sub_array[] = '<button type="button" onClick="ver('.$row["tick_id"].');" id="'.$row["tick_id"].'" class="btn btn-inline btn-info btn-sm ladda-button"><div><i class="fa fa-eye"></i></div></button>';
                $data[] = $sub_array;
            }
            $results =  array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
        break;

        case "listar":
            $datos=$ticket->listar_ticket();
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["tick_id"];
                $sub_array[] = $row["cat_nom"];
                $sub_array[] = $row["tick_titulo"];
                $sub_array[] = $row["prio_nom"];
                
                if($row["tick_estado"]=='Abierto'){
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }else{
                    $sub_array[] = '<a onClick="CambiarEstado('.$row["tick_id"].')"><span class="label label-pill label-danger">Cerrado</span></a>';
                }

                $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_crea"]));

                if($row["fech_asig"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin asignar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_asig"]));
                }

                if($row["fech_cierre"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin cerrar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_cierre"]));
                }

                if($row["usu_asig"]==null){
                    $sub_array[] = '<a onClick="asignar('.$row["tick_id"].');"><span class="label label-pill label-warning">Sin asignar</span></a>';
                }else{
                    $datos1 = $usuario->get_usuario_x_id($row["usu_asig"]);
                    foreach($datos1  as $row1){
                        $sub_array[] = '<span class="label label-pill label-success">'. $row1["usu_nom"].'</span>';
                    }
                }

                $sub_array[] = '<button type="button" onClick="ver('.$row["tick_id"].');" id="'.$row["tick_id"].'" class="btn btn-inline btn-info btn-sm ladda-button"><div><i class="fa fa-eye"></i></div></button>';
                $data[] = $sub_array;
            }
            $results =  array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
        break;

        case "listardetalle":
            $datos=$ticket->listar_ticketdetalle_x_ticket($_POST["tick_id"]);
            ?>
                <?php
                    foreach($datos as $row){
                        ?>
                            <article class="activity-line-item box-typical">
                                <div class="activity-line-date">
                                    <?php echo date("d/m/Y", strtotime($row["fech_crea"]));?> <!-- Muestra la fecha del chat del ticket -->
                                </div>
                                <header class="activity-line-item-header">
                                    <div class="activity-line-item-user">
                                        <div class="activity-line-item-user-photo">
                                            <a href="#">
                                                <img src="../../public/img/<?php echo $row["rol_id"]?>.png" alt="">
                                            </a>
                                        </div>
                                        <div class="activity-line-item-user-name"><?php echo $row['usu_nom'].' '.$row['usu_ape'];?></div>
                                        <div class="activity-line-item-user-status">
                                            <?php 
                                                if($row['rol_id']==1){
                                                    echo 'Usuario';
                                                }else{
                                                    echo 'Soporte';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </header>
                                <div class="activity-line-action-list">
                                    <section class="activity-line-action">
                                        <!-- Muestra la hora del chat del ticket -->
                                        <div class="time"><?php echo date("H:i", strtotime($row["fech_crea"]));?></div>
                                        <div class="cont">
                                            <div class="cont-in">
                                                <p>
                                                    <?php echo $row['tickd_descrip'];?>
                                                </p>

                                                <br>

                                                <?php
                                                    $datos_det = $documento->get_documento_detalle_x_ticketd($row["tickd_id"]);
                                                    if (is_array($datos_det)==true and count($datos_det)>0){
                                                        ?>
                                                            <p><strong>Documentos Adicionales</strong></p>
                                                            <p>
                                                                <table class="table table-bordered table-striped table-vcenter js-dataTable-fulll">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 60%;">Nombre</th>
                                                                            <th style="width: 40%;"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                            foreach($datos_det as $row_det){
                                                                        ?>
                                                                            <tr>
                                                                                <td><?php echo $row_det["det_nom"]; ?></td>
                                                                                <td>
                                                                                    <a href="../../public/document_detalle/<?php echo $row_det["tickd_id"] ?>/<?php echo $row_det["det_nom"] ?>" target="_blank" class="btn btn-inline btn-primary btn-sm">Ver</a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>    
                                                            </p>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </article>
                        <?php
                    }
                ?>
            <?php
        break;

        case "mostrar";
            $datos=$ticket->listar_ticket_x_id($_POST["tick_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["tick_id"] = $row["tick_id"];
                    $output["usu_id"] = $row["usu_id"];
                    $output["cat_id"] = $row["cat_id"];

                    $output["tick_titulo"] = $row["tick_titulo"];
                    $output["tick_descrip"] = $row["tick_descrip"];

                    if ($row["tick_estado"]=="Abierto"){
                        $output["tick_estado"] = '<span class="label label-pill label-success">Abierto</span>';
                    }else{
                        $output["tick_estado"] = '<span class="label label-pill label-danger">Cerrado</span>';
                    }
                    $output["tick_estado_texto"] = $row["tick_estado"];

                    $output["fech_crea"] = date("d/m/Y H:i:s", strtotime($row["fech_crea"]));
                    $output["fech_cierre"] = date("d/m/Y H:i:s", strtotime($row["fech_cierre"]));
                    $output["usu_nom"] = $row["usu_nom"];
                    $output["usu_ape"] = $row["usu_ape"];
                    $output["cat_nom"] = $row["cat_nom"];
                    $output["cats_nom"] = $row["cats_nom"];
                    $output["tick_estre"] = $row["tick_estre"];
                    $output["tick_coment"] = $row["tick_coment"];
                    $output["prio_nom"] = $row["prio_nom"];
                }
                echo json_encode($output);
            }   
        break;

        case "insertdetalle":
            $datos = $ticket->insert_ticketdetalle($_POST["tick_id"],$_POST["usu_id"],$_POST["tickd_descrip"]);
            if (is_array($datos)==true and count($datos)>0){
                foreach ($datos as $row){
                    /* Obtener tickd_id de $datos */
                    $output["tickd_id"] = $row["tickd_id"];
                    /* Consultamos si vienen archivos desde la vista */
                    if (empty($_FILES['files']['name'])){

                    }else{
                        /* Contar registros */
                        $countfiles = count($_FILES['files']['name']);
                        /* Ruta de los documentos */
                        $ruta = "../public/document_detalle/".$output["tickd_id"]."/";
                        /* Crear un array de todos los archivos */
                        $files_arr = array();
                        /* Consultar si la ruta existe, en caso de que no, se crea */
                        if(!file_exists($ruta)){
                            mkdir($ruta, 0777, true);
                        }

                        /* Recorrer todos los registros */
                        for($index = 0; $index < $countfiles; $index++){
                            $doc1 = $_FILES['files']['tmp_name'][$index];
                            $destino = $ruta.$_FILES['files']['name'][$index];

                            $documento->insert_documento_detalle($output["tickd_id"], $_FILES['files']['name'][$index]);

                            move_uploaded_file($doc1, $destino);
                        }

                    }
                }
            }
            echo json_encode($datos);
        break;    
        /* Muestra el total de tickets */
        case "total";
            $datos=$ticket->get_ticket_total();  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
        break;
        /* Muestra el total de tickets abiertos */
        case "totalabierto";
            $datos=$ticket->get_ticket_totalabierto();  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
        break;
        /* Muestra el total de tickets cerrados */
        case "totalcerrado";
            $datos=$ticket->get_ticket_totalcerrado();  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
        break;
        /* Muestra un gráfico con el total de tickets por categoría */
        case "grafico";
            $datos=$ticket->get_ticket_grafico();  
            echo json_encode($datos);
        break;

        case "encuesta":
            $ticket->insert_encuesta($_POST["tick_id"],$_POST["tick_estre"],$_POST["tick_coment"]);
        break;
    }
?>
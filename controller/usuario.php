<?php
    require_once("../config/conexion.php");
    require_once("../models/Usuario.php");
    $usuario = new Usuario();

    $key = "ll4v#s7St3m45ioC0%$";
    $cipher="aes-256-cbc";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

    switch($_GET["op"]){
        //Crear o actualizar usuario
        case "guardaryeditar":
            if(empty($_POST["usu_id"])){       
                $usuario->insert_usuario($_POST["usu_nom"],$_POST["usu_ape"],$_POST["usu_correo"],$_POST["usu_pass"],$_POST["rol_id"]);     
            }
            else {
                $usuario->update_usuario($_POST["usu_id"],$_POST["usu_nom"],$_POST["usu_ape"],$_POST["usu_correo"],$_POST["usu_pass"],$_POST["rol_id"]);
            }
        break;
        /* Listar a los usuarios disponibles */
        case "listar":
            $datos=$usuario->get_usuario();
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["usu_nom"];
                $sub_array[] = $row["usu_ape"];
                $sub_array[] = $row["usu_correo"];
                $sub_array[] = $row["usu_pass"];

                if ($row["rol_id"]=="1"){
                    $sub_array[] = '<span class="label label-pill label-success">Usuario</span>';
                }else{
                    $sub_array[] = '<span class="label label-pill label-info">Soporte</span>';
                }

                $sub_array[] = '<button type="button" onClick="editar('.$row["usu_id"].');"  id="'.$row["usu_id"].'" class="btn btn-inline btn-secondary btn-sm ladda-button"><i class="fa fa-edit"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["usu_id"].');"  id="'.$row["usu_id"].'" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';                
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
        break;
        /* Eliminar usuario */
        case "eliminar":
            $usuario->delete_usuario($_POST["usu_id"]);
        break;
        /* Mostrar al usuario por ID */
        case "mostrar";
            $datos=$usuario->get_usuario_x_id($_POST["usu_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["usu_id"] = $row["usu_id"];
                    $output["usu_nom"] = $row["usu_nom"];
                    $output["usu_ape"] = $row["usu_ape"];
                    $output["usu_correo"] = $row["usu_correo"];
                    /* Decifrar el password para mostrarlo en la ventana de editar */
                    $iv_dec = substr(base64_decode($row["usu_pass"]),0,openssl_cipher_iv_length($cipher));
                    $cifradoSinIV = substr(base64_decode($row["usu_pass"]), openssl_cipher_iv_length($cipher));
                    $decifrado = openssl_decrypt($cifradoSinIV,$cipher,$key, OPENSSL_RAW_DATA,$iv_dec);

                    $output["usu_pass"] = $decifrado;
                    $output["rol_id"] = $row["rol_id"];
                }
                echo json_encode($output);
            }   
        break;
        /* Muestra el total de tickets por usuario */
        case "total";
            $datos=$usuario->get_usuario_total_x_id($_POST["usu_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
        break;
        /* Muestra el total de tickets abiertos por usuario */
        case "totalabierto";
            $datos=$usuario->get_usuario_totalabierto_x_id($_POST["usu_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
        break;
        /* Muestra el total de tickets cerrados por usuario */
        case "totalcerrado";
            $datos=$usuario->get_usuario_totalcerrado_x_id($_POST["usu_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
        break;
        /* Muestra un gráfico con el total de tickets por categoría realizados por el usuario */
        case "grafico";
            $datos=$usuario->get_usuario_grafico($_POST["usu_id"]);  
            echo json_encode($datos);
        break;

        case "combo";
            $datos=$usuario->get_usuario_x_rol();
            if(is_array($datos)==true and count($datos)>0){
                $html.="<option label='Seleccionar'></option>";
                foreach($datos as $row){
                    $html.= "<option value='".$row['usu_id']."'>".$row['usu_nom']."</option>";
                }
                echo $html;
            }
        break;

        /* Cambiar password del usuario */
        case "password":
            $cifrado = openssl_encrypt($_POST["usu_pass"],$cipher,$key, OPENSSL_RAW_DATA,$iv);
            $textoCifrado  = base64_encode($iv . $cifrado);

            $usuario->update_usuario_pass($_POST["usu_id"], $textoCifrado);
        break;

        case "correo":
            $datos=$usuario->get_usuario_x_correo($_POST["usu_correo"]);
            if(is_array($datos)==true and count($datos)>0){

                echo "Existe";
            }else{
                echo "Error";
            }
            break;
        
    }
?>
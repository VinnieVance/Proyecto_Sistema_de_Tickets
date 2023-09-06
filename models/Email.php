<?php
    /* librerias necesarias para que el proyecto pueda enviar emails */
    require("class.phpmailer.php");
    include("class.smtp.php");

    /* llamada de las clases necesarias que se usaran en el envio del mail */
    require_once("../config/conexion.php");
    require_once("../models/Ticket.php");

    class Email extends PHPMailer{
        protected $gCorreo = 'acorteg001@alumno.uaemex.mx';
        protected $gContrasena = 'Gxm51ngpL8b';
        /* Funcion para enviar alerta de ticket abierto por email */
        public function ticket_abierto($tick_id){
            $ticket = new Ticket();
            $datos = $ticket->listar_ticket_x_id($tick_id);
            foreach ($datos as $row){
                $id = $row["tick_id"];
                $usu = $row["usu_nom"];
                $titulo = $row["tick_titulo"];
                $categoria = $row["cat_nom"];
                $correo = $row["usu_correo"];
            }

            $this->IsSMTP();
            $this->Host = 'smtp.office365.com'; //Aquí el server
            $this->Port = 587; //Aquí el puerto
            $this->SMTPAuth = true;
            $this->Username = $this->gCorreo;
            $this->Password = $this->gContrasena;
            $this->From = $this->gCorreo;
            $this->SMTPSecure = 'tls';
            $this->FromName = "Ticket Abierto ";
            $this->CharSet = 'UTF8';
            $this->addAddress($correo);
            $this->WordWrap = 50;
            $this->IsHTML(true);
            $this->Subject = "Ticket Abierto";
            //Igual//
            $cuerpo = file_get_contents('../public/NuevoTicket.html'); /* Ruta del template en formato HTML */
            /* parametros del template a remplazar */
            $cuerpo = str_replace("xnroticket", $id, $cuerpo);
            $cuerpo = str_replace("lblNomUsu", $usu, $cuerpo);
            $cuerpo = str_replace("lblTitu", $titulo, $cuerpo);
            $cuerpo = str_replace("lblCate", $categoria, $cuerpo);

            $this->Body = $cuerpo;
            $this->AltBody = strip_tags("Ticket Abierto");
            return $this->Send();

        }
        /* Funcion para enviar alerta de ticket cerrado por email */
        public function ticket_cerrado($tick_id){
            $ticket = new Ticket();
            $datos = $ticket->listar_ticket_x_id($tick_id);
            foreach ($datos as $row){
                $id = $row["tick_id"];
                $usu = $row["usu_nom"];
                $titulo = $row["tick_titulo"];
                $categoria = $row["cat_nom"];
                $correo = $row["usu_correo"];
            }

            //IGual//
            $this->IsSMTP();
            $this->Host = 'smtp.office365.com';//Aqui el server
            $this->Port = 587;//Aqui el puerto
            $this->SMTPAuth = true;
            $this->Username = $this->gCorreo;
            $this->Password = $this->gContrasena;
            $this->From = $this->gCorreo;
            $this->SMTPSecure = 'tls';
            $this->FromName = "Ticket Cerrado ";
            $this->CharSet = 'UTF8';
            $this->addAddress($correo);
            $this->WordWrap = 50;
            $this->IsHTML(true);
            $this->Subject = "Ticket Cerrado";
            //Igual//
            $cuerpo = file_get_contents('../public/CerradoTicket.html'); /* Ruta del template en formato HTML */
            /* parametros del template a remplazar */
            $cuerpo = str_replace("xnroticket", $id, $cuerpo);
            $cuerpo = str_replace("lblNomUsu", $usu, $cuerpo);
            $cuerpo = str_replace("lblTitu", $titulo, $cuerpo);
            $cuerpo = str_replace("lblCate", $categoria, $cuerpo);

            $this->Body = $cuerpo;
            $this->AltBody = strip_tags("Ticket Cerrado");
            return $this->Send();
        }
        /* Funcion para enviar alerta de ticket aisgnado por email */
        public function ticket_asignado($tick_id){
            $ticket = new Ticket();
            $datos = $ticket->listar_ticket_x_id($tick_id);
            foreach ($datos as $row){
                $id = $row["tick_id"];
                $usu = $row["usu_nom"];
                $titulo = $row["tick_titulo"];
                $categoria = $row["cat_nom"];
                $correo = $row["usu_correo"];
            }

            //IGual//
            $this->IsSMTP();
            $this->Host = 'smtp.office365.com';//Aqui el server
            $this->Port = 587;//Aqui el puerto
            $this->SMTPAuth = true;
            $this->Username = $this->gCorreo;
            $this->Password = $this->gContrasena;
            $this->From = $this->gCorreo;
            $this->SMTPSecure = 'tls';
            $this->FromName = "Ticket Asignado ";
            $this->CharSet = 'UTF8';
            $this->addAddress($correo);
            $this->WordWrap = 50;
            $this->IsHTML(true);
            $this->Subject = "Ticket Asignado";
            //Igual//
            $cuerpo = file_get_contents('../public/AsignarTicket.html'); /* Ruta del template en formato HTML */
            /* parametros del template a remplazar */
            $cuerpo = str_replace("xnroticket", $id, $cuerpo);
            $cuerpo = str_replace("lblNomUsu", $usu, $cuerpo);
            $cuerpo = str_replace("lblTitu", $titulo, $cuerpo);
            $cuerpo = str_replace("lblCate", $categoria, $cuerpo);

            $this->Body = $cuerpo;
            $this->AltBody = strip_tags("Ticket Asignado");
            return $this->Send();
        }
    }

?>
<?php
    /* Librerias necesarias para que el proyecto pueda enviar emails */
    require '../include/vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    /* llamada de las clases necesarias que se usaran en el envio del mail */
    require_once("../config/conexion.php");
    require_once("../models/Ticket.php");
    require_once("../Models/Usuario.php");

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
            $this->SMTPSecure = 'tls';

            $this->Username = $this->gCorreo;
            $this->Password = $this->gContrasena;
            $this->setFrom($this->gCorreo, "Ticket Abierto ".$id);

            $this->CharSet = 'UTF8';
            $this->addAddress($correo);
            $this->addAddress($_SESSION["correo"]);
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

            try{
                $this->Send();
                return true;
            } catch(Exception $e){
                return false;
            }

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
            $this->SMTPSecure = 'tls';

            $this->setFrom($this->gCorreo, "Ticket Cerrado ".$id);

            $this->CharSet = 'UTF8';
            $this->addAddress($correo);
            $this->addAddress($_SESSION["usu_correo"]);
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
            
            try{
                $this->Send();
                return true;
            } catch(Exception $e){
                return false;
            }
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
            $this->SMTPSecure = 'tls';

            $this->setFrom($this->gCorreo, "Ticket Asignado ".$id);
            $this->CharSet = 'UTF8';
            $this->addAddress($correo);
            $this->addAddress($_SESSION["usu_correo"]);
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
            
            try{
                $this->Send();
                return true;
            } catch(Exception $e){
                return false;
            }
        }
        /* Funcion para recuperar contraseña por email */
        public function recuperar_contrasenna($usu_correo){
            $usuario = new Usuario();

            $usuario->get_cambiar_contra_recuperar($usu_correo);

            $datos = $usuario->get_usuario_x_correo($usu_correo);
            foreach ($datos as $row){
                $usu_id = $row["usu_id"];
                $usu_ape = $row["usu_ape"];
                $usu_nom = $row["usu_nom"];
                $correo = $row["usu_correo"];
                $usu_pass= $row["usu_pass"];
            }

            $this->IsSMTP();
            $this->Host = 'smtp.office365.com'; //Aquí el server
            $this->Port = 587; //Aquí el puerto
            $this->SMTPAuth = true;
            $this->SMTPSecure = 'tls';

            $this->Username = $this->gCorreo;
            $this->Password = $this->gContrasena;
            $this->setFrom($this->gCorreo, "Recuperar Contraseña");

            $this->CharSet = 'UTF8';
            $this->addAddress($correo);
            $this->IsHTML(true);
            $this->Subject = "Recuperar Contraseña";
            //Igual//
            $cuerpo = file_get_contents('../public/RecuperarContra.html'); /* Ruta del template en formato HTML */
            /* parametros del template a remplazar */
            $cuerpo = str_replace("xusunom", $usu_nom, $cuerpo);
            $cuerpo = str_replace("xusuape", $usu_ape, $cuerpo);
            $cuerpo = str_replace("xnuevopass", $usu_pass, $cuerpo);

            $this->Body = $cuerpo;
            $this->AltBody = strip_tags("Recuperar Contraseña");

            try{
                $this->Send();
                $usuario->encriptar_nueva_contra($usu_id,$usu_pass);
                return true;
            } catch(Exception $e){
                return false;
            }

        }
    }

?>
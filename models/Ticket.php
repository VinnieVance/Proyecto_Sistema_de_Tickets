<?php
    class Ticket extends Conectar{ #Clase Categoria extendida de Conectar
        //Funcion para guardar datos del ticket en la BD
        public function insert_ticket($usu_id, $cat_id, $cats_id, $tick_titulo, $tick_descrip){
            $conectar = parent::conexion();
            parent::set_names();
            $sql="INSERT INTO tm_ticket 
            (tick_id, usu_id, cat_id, cats_id, tick_titulo, tick_descrip, tick_estado, fech_crea, usu_asig, fech_asig, est) 
            VALUES 
            (NULL,?,?,?,?,?,'Abierto',now(),NULL,NULL,'1');";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->bindValue(2, $cat_id);
            $sql->bindValue(3, $cats_id);
            $sql->bindValue(4, $tick_titulo);
            $sql->bindValue(5, $tick_descrip);
            $sql->execute();

            $sql1="SELECT LAST_INSERT_ID() AS 'tick_id';";
            $sql1=$conectar->prepare($sql1);
            $sql1->execute();
            return $resultado=$sql1->fetchAll(pdo::FETCH_ASSOC); /* Para traer solo el dato de tick ID */
        }
        //Funcion para visualizar tickets por usuario
        public function listar_ticket_x_usu($usu_id){
            $conectar = parent::conexion();
            parent::set_names();
            $sql="SELECT
                tm_ticket.tick_id,
                tm_ticket.usu_id,
                tm_ticket.cat_id,
                tm_ticket.tick_titulo,
                tm_ticket.tick_descrip,
                tm_ticket.tick_estado,
                tm_ticket.fech_crea,
                tm_ticket.usu_asig,
                tm_ticket.fech_asig,
                tm_usuario.usu_nom,
                tm_usuario.usu_ape,
                tm_categoria.cat_nom
                FROM
                tm_ticket
                INNER join tm_categoria on tm_ticket.cat_id = tm_categoria.cat_id
                INNER join tm_usuario on tm_ticket.usu_id = tm_usuario.usu_id
                WHERE
                tm_ticket.est= 1
                AND tm_usuario.usu_id=?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        //Funcion para visualizar ticket por ID
        public function listar_ticket_x_id($tick_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT 
                tm_ticket.tick_id,
                tm_ticket.usu_id,
                tm_ticket.cat_id,
                tm_ticket.cats_id,
                tm_ticket.tick_titulo,
                tm_ticket.tick_descrip,
                tm_ticket.tick_estado,
                tm_ticket.fech_crea,
                tm_ticket.tick_estre,
                tm_ticket.tick_coment,
                tm_usuario.usu_nom,
                tm_usuario.usu_ape,
                tm_usuario.usu_correo,
                tm_categoria.cat_nom,
                tm_subcategoria.cats_nom
                FROM 
                tm_ticket
                INNER join tm_categoria on tm_ticket.cat_id = tm_categoria.cat_id
                INNER join tm_subcategoria on tm_ticket.cats_id = tm_subcategoria.cats_id
                INNER join tm_usuario on tm_ticket.usu_id = tm_usuario.usu_id
                WHERE
                tm_ticket.est = 1
                AND tm_ticket.tick_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        //Funcion para que el soporte visualize todos los tickets
        public function listar_ticket(){
            $conectar = parent::conexion();
            parent::set_names();
            $sql="SELECT
                tm_ticket.tick_id,
                tm_ticket.usu_id,
                tm_ticket.cat_id,
                tm_ticket.tick_titulo,
                tm_ticket.tick_descrip,
                tm_ticket.tick_estado,
                tm_ticket.fech_crea,
                tm_ticket.usu_asig,
                tm_ticket.fech_asig,
                tm_usuario.usu_nom,
                tm_usuario.usu_ape,
                tm_categoria.cat_nom
                FROM
                tm_ticket
                INNER join tm_categoria on tm_ticket.cat_id = tm_categoria.cat_id
                INNER join tm_usuario on tm_ticket.usu_id = tm_usuario.usu_id
                WHERE
                tm_ticket.est= 1";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        //Funcion para visualizar detalle de cada ticket
        public function listar_ticketdetalle_x_ticket($tick_id){
            $conectar = parent::conexion();
            parent::set_names();
            $sql="SELECT 
                td_ticketdetalle.tickd_id,
                td_ticketdetalle.tickd_descrip,
                td_ticketdetalle.fech_crea,
                tm_usuario.usu_nom,
                tm_usuario.usu_ape,
                tm_usuario.rol_id
                FROM 
                td_ticketdetalle
                INNER JOIN tm_usuario ON td_ticketdetalle.usu_id = tm_usuario.usu_id
                WHERE 
                tick_id=?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        //Funcion para chatear dentro de un ticket
        public function insert_ticketdetalle($tick_id,$usu_id,$tickd_descrip){
            $conectar= parent::conexion();
            parent::set_names();
                $sql="INSERT INTO td_ticketdetalle 
                (tickd_id,tick_id,usu_id,tickd_descrip,fech_crea,est) 
                VALUES (NULL,?,?,?,now(),'1');";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->bindValue(2, $usu_id);
            $sql->bindValue(3, $tickd_descrip);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        //Funcion para insertar en la tabla de detalle de ticket cuando cierre
        public function insert_ticketdetalle_cerrar($tick_id,$usu_id){
            $conectar= parent::conexion();
            parent::set_names();
                $sql="INSERT INTO td_ticketdetalle 
                (tickd_id,tick_id,usu_id,tickd_descrip,fech_crea,est) 
                VALUES 
                (NULL,?,?,'Ticket Cerrado...',now(),'1');";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->bindValue(2, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        //Funcion para insertar en la tabla de detalle de ticket cuando cierre
        public function insert_ticketdetalle_reabrir($tick_id,$usu_id){
            $conectar= parent::conexion();
            parent::set_names();
                $sql="INSERT INTO td_ticketdetalle 
                (tickd_id,tick_id,usu_id,tickd_descrip,fech_crea,est) 
                VALUES 
                (NULL,?,?,'Ticket Re-abierto...',now(),'1');";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->bindValue(2, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        //Funcion para actualizar estado del ticket
        public function update_ticket($tick_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="UPDATE tm_ticket 
                SET
                    tick_estado = 'Cerrado',
                    fech_cierre = now()
                where
                    tick_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        //Funcion para reabrir ticket
        public function reabrir_ticket($tick_id){
           $conectar= parent::conexion();
           parent::set_names();
           $sql="UPDATE tm_ticket 
               SET
                   tick_estado = 'Abierto'
               where
                   tick_id = ?";
           $sql=$conectar->prepare($sql);
           $sql->bindValue(1, $tick_id);
           $sql->execute();
           return $resultado=$sql->fetchAll();
        }
        /* Actualizar datos de ticket asignado a un soporte */
        public function update_ticket_asignacion($tick_id,$usu_asig){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="UPDATE tm_ticket 
                SET
                    usu_asig = ?,
                    fech_asig =now()
                where
                    tick_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_asig);
            $sql->bindValue(2, $tick_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* Obtener el total de tickets */
        public function get_ticket_total(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_ticket";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* Consulta para Obtener el total de tickets abiertos */
        public function get_ticket_totalabierto(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_ticket where tick_estado='Abierto'";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* Consulta para Obtener el total de tickets cerrados */
        public function get_ticket_totalcerrado(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_ticket where tick_estado='Cerrado'";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        } 
        
        /* Consulta para Obtener el numero de tickets por categoria */
        public function get_ticket_grafico(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT tm_categoria.cat_nom as nom,COUNT(*) AS total
                FROM   tm_ticket  JOIN  
                    tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id  
                WHERE    
                tm_ticket.est = 1
                GROUP BY 
                tm_categoria.cat_nom 
                ORDER BY total DESC";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        public function insert_encuesta($tick_id, $tick_estre, $tick_coment){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="UPDATE tm_ticket 
                SET
                    tick_estre = ?,
                    tick_coment = ?
                where
                    tick_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_estre);
            $sql->bindValue(2, $tick_coment);
            $sql->bindValue(3, $tick_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
    }

?>
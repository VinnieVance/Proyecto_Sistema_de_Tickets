<?php
    class Prioridad extends Conectar{ #Clase Categoria extendida de Conectar

        public function get_prioridad(){
            $conectar = parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_prioridad WHERE est=1;";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* Funcion para insertar nueva prioridad */
        public function insert_prioridad($prio_nom){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="INSERT INTO tm_prioridad (prio_id, prio_nom, est) 
            VALUES 
            (NULL,?,'1');";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $prio_nom);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* Funcion para actualizar una prioridad */
        public function update_prioridad($prio_nom, $prio_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="UPDATE tm_prioridad set
                prio_nom = ?
                WHERE
                prio_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $prio_nom);
            $sql->bindValue(2, $prio_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* Funcion para eliminar una prioridad */
        public function delete_prioridad($prio_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="UPDATE tm_prioridad 
                SET 
                    est='0'
                WHERE
                    prio_id=?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $prio_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /*  Funcion mostrar las prioridades por id*/
        public function get_prioridad_x_id($prio_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_prioridad where prio_id=?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $prio_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
    }

?>
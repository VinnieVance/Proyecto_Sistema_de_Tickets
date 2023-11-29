<?php
    class Usuario extends Conectar{ #Clase usuario extendida de la clase Conectar
        #Funcion login para iniciar sesion de usuario
        public function login(){
            $conectar=parent::conexion();
            parent::set_names();
            if(isset($_POST["enviar"])){ #Correo y contraseña para entrar
                $correo = $_POST["usu_correo"];
                $pass = $_POST["usu_pass"];
                $rol = $_POST["rol_id"];
                if(empty($correo) and empty($pass)){ #Si estan vacios los campos, se retorna un mensaje de error
                    header("Location:".conectar::ruta()."index.php?m=2");
                    exit();
                }else{ #Proceso de inicio de sesion
                    $sql = "SELECT * FROM tm_usuario WHERE usu_correo = ? and rol_id=? and est= 1";
                    $stmt=$conectar->prepare($sql);
                    $stmt->bindValue(1, $correo);
                    $stmt->bindValue(2, $rol);
                    $stmt->execute();
                    $resultado = $stmt->fetch();
                    if($resultado){
                        $textocifrado = $resultado["usu_pass"];

                        $key = "ll4v#s7St3m45ioC0%$";
                        $cipher="aes-256-cbc";
                        $iv_dec = substr(base64_decode($textocifrado),0,openssl_cipher_iv_length($cipher));
                        $cifradoSinIV = substr(base64_decode($textocifrado), openssl_cipher_iv_length($cipher));
                        $decifrado = openssl_decrypt($cifradoSinIV,$cipher,$key, OPENSSL_RAW_DATA,$iv_dec);

                        if($decifrado==$pass){
                            #Si la variable resultado es un array y tiene mas de 1 elemento
                            if(is_array($resultado) and count($resultado)>0){
                                $_SESSION["usu_id"]=$resultado["usu_id"];
                                $_SESSION["usu_nom"]=$resultado["usu_nom"];
                                $_SESSION["usu_ape"]=$resultado["usu_ape"];
                                $_SESSION["rol_id"]=$resultado["rol_id"];
                                header("Location:".Conectar::ruta()."view/Home/"); #Ir a la pagina Home
                                exit();
                            }else{ #Si no, el usuario no exite
                                header("Location:".Conectar::ruta()."index.php?m=1");
                                exit();
                            }
                        }
                    }
                }
            }
        }
        /* Funcion para insertar un usuario */
        public function insert_usuario($usu_nom,$usu_ape,$usu_correo,$usu_pass,$rol_id){

            $key = "ll4v#s7St3m45ioC0%$";
            $cipher="aes-256-cbc";
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
            $cifrado = openssl_encrypt($usu_pass,$cipher,$key, OPENSSL_RAW_DATA,$iv);
            $textoCifrado  = base64_encode($iv . $cifrado);

            $conectar= parent::conexion();
            parent::set_names();
            $sql="INSERT INTO tm_usuario (usu_id, usu_nom, usu_ape, usu_correo, usu_pass, rol_id, fech_crea, fech_modi, fech_elim, est) 
                    VALUES (NULL,?,?,?,?,?,now(), NULL, NULL, '1');";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_nom);
            $sql->bindValue(2, $usu_ape);
            $sql->bindValue(3, $usu_correo);
            $sql->bindValue(4, $textoCifrado);
            $sql->bindValue(5, $rol_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* Funcion para actualizar un usuario */
        public function update_usuario($usu_id,$usu_nom,$usu_ape,$usu_correo,$usu_pass,$rol_id){
            $key = "ll4v#s7St3m45ioC0%$";
            $cipher="aes-256-cbc";
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
            $cifrado = openssl_encrypt($usu_pass,$cipher,$key, OPENSSL_RAW_DATA,$iv);
            $textoCifrado  = base64_encode($iv . $cifrado);
            
            $conectar= parent::conexion();
            parent::set_names();
            $sql="UPDATE tm_usuario set
                usu_nom = ?,
                usu_ape = ?,
                usu_correo = ?,
                usu_pass = ?,
                rol_id = ?,
                fech_modi = now()
                WHERE
                usu_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_nom);
            $sql->bindValue(2, $usu_ape);
            $sql->bindValue(3, $usu_correo);
            $sql->bindValue(4, $textoCifrado);
            $sql->bindValue(5, $rol_id);
            $sql->bindValue(6, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* Funcion para eliminar un usuario */
        public function delete_usuario($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="UPDATE tm_usuario 
                SET 
                    est='0',
                    fech_elim=now()
                where usu_id=?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* Funcion para mostrar todos los usuarios */
        public function get_usuario(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_usuario where est='1'";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* Funcion para mostrar los usuarios por rol */
        public function get_usuario_x_rol(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_usuario where est='1' and rol_id=2";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /*  Funcion mostrar los usuarios por ID */
        public function get_usuario_x_id($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_usuario where usu_id=?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* Mostrar grafico con total de tickets */
        public function get_usuario_total_x_id($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_ticket where usu_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* Mostrar grafico con total de tickets abiertos */
        public function get_usuario_totalabierto_x_id($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_ticket where usu_id = ? and tick_estado='Abierto'";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* Mostrar grafico con total de tickets cerrados */
        public function get_usuario_totalcerrado_x_id($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_ticket where usu_id = ? and tick_estado='Cerrado'";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* Obtener el gráfico estadistíco por usuario */
        public function get_usuario_grafico($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT tm_categoria.cat_nom as nom,COUNT(*) AS total
                FROM   tm_ticket  JOIN  
                    tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id  
                WHERE    
                tm_ticket.est = 1
                and tm_ticket.usu_id = ?
                GROUP BY 
                tm_categoria.cat_nom 
                ORDER BY total DESC";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        } 
        /*  Funcion actualizar la contraseña del usuario */
        public function update_usuario_pass($usu_id, $usu_pass){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="UPDATE tm_usuario
            SET
                usu_pass = ?
            WHERE
                usu_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_pass);
            $sql->bindValue(2, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
    }
?>
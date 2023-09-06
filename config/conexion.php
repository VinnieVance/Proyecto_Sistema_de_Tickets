<?php
    session_start();
    #Conexion con la Base de Datos
    class Conectar{
        protected $dbh;
        
        protected function Conexion(){
            try{
                $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=UAEM_sistema_tickets","root","");
                return $conectar;
            } catch (Exception $e){
                print "¡Error BD!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
        #Para evitar problemas con mayusculas y tildes
        public function set_names(){
            return $this->dbh->query("SET NAMES 'utf8'");
        }
        #Validar ruta del proyecto
        public static function ruta(){
            return "http://localhost:80/Proyecto Sistema de Tickets/";
        }
    }
?>
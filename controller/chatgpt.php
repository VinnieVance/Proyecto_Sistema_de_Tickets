<?php
    /* llamada a las clases necesarias */
    require_once("../config/conexion.php");
    require_once("../models/Chatgpt.php");
    $chatgpt = new Chatgpt();

    /* opciones del controlador */
    switch ($_GET["op"]) {
        /*  Responder al primer prompt del usuario con chatgpt */
        case "respuestaia":
            $datos = $chatgpt->get_respuestaia($_POST["tick_id"]);
            echo ($datos);
            break;
    }
?>
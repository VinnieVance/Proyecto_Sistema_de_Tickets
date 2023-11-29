<?php
    /* llamada a las clases necesarias */
    require_once("../config/conexion.php");
    require_once("../models/Chatgpt.php");
    $chatgpt = new Chatgpt();

    $key = "ll4v#s7St3m45ioC0%$";
    $cipher="aes-256-cbc";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

    /* opciones del controlador */
    switch ($_GET["op"]) {
        /*  Responder al primer prompt del usuario con chatgpt */
        case "respuestaia":
            /* Decifrado del ID */
            $iv_dec = substr(base64_decode($_POST["tick_id"]),0,openssl_cipher_iv_length($cipher));
            $cifradoSinIV = substr(base64_decode($_POST["tick_id"]), openssl_cipher_iv_length($cipher));
            $decifrado = openssl_decrypt($cifradoSinIV,$cipher,$key, OPENSSL_RAW_DATA,$iv_dec);

            $datos = $chatgpt->get_respuestaia($decifrado);
            echo ($datos);
            break;
    }
?>
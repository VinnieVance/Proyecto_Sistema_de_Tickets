<?php
    /* Datos a cifrar */
    $data="P@nchoV1lla";
    /* Clave de cifrado (asegurate de usar una clave segura en un entorno real) */
    $key = "ll4v#s7St3m45ioC0%$";
    /* Metodo de cifrado (puedes  usar 'aes-256-cbc' u otros algoritmos soportados por OpenSSL) */
    $cipher="aes-256-cbc";
    /* Vector de inicialización (IV) necesario para el cifrado */
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    /* Cifrado */
    $cifrado = openssl_encrypt($data,$cipher,$key, OPENSSL_RAW_DATA,$iv);
    /* Concatenar el IV al texto cifrado */
    $textoCifrado  = base64_encode($iv . $cifrado);
    /* Obtener el IV del texto cifrado */
    $iv_dec = substr(base64_decode($textoCifrado),0,openssl_cipher_iv_length($cipher));
    /* Obtener el texto cifrado sin el IV */
    $cifradoSinIV = substr(base64_decode($textoCifrado), openssl_cipher_iv_length($cipher));
    /* Decifrado */
    $decifrado = openssl_decrypt($cifradoSinIV,$cipher,$key, OPENSSL_RAW_DATA,$iv_dec);

    echo "Texto Cifrado: " , $textoCifrado;

    echo "<br> Texto Decifrado: ". $decifrado;
?>
<?php
    $apikey = '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';


    $data = [
        'model' => 'text-davinci-002', //Modelo
        'prompt' => 'Responde como un técnico de soporte TI: Problemas de inicio de sesion al SAP', //Pregunta
        'temperature' => 0.7, //Que tan inteligente se necesita que sea ChatGPT
        'max_tokens' => 300, //Maximo de paalabras que puede responder ChatGPT
        'n' => 1,
        'stop' => ['\n']
    ];

    $ch = curl_init('https://api.openai.com/v1/completions');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apikey
    ));

    $response = curl_exec($ch);
    $responseArr = json_decode($response, true);

    /* print($response); */

    echo $responseArr['choices'][0]['text'];

?>
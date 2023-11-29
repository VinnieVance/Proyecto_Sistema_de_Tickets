<?php
    $apikey = 'OpenAI API KEY Here';

    $model = "gpt-3.5-turbo";

    $messages = [
        [
            'role' => 'system',
            'content' => 'Eres un técnico en TI.'
        ],
        [
            'role' => 'user',
            'content' => '¿Qué es un CPU?'
        ]
    ];

    $data = [
        "model" => $model, //Modelo
        "messages" => $messages,
        "temperature" => 0.5, //Que tan inteligente se necesita que sea ChatGPT
        "max_tokens" => 1024 //Maximo de paalabras que puede responder ChatGPT
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apikey
    ));

    $response = curl_exec($ch);
    
    echo $response;

?>
<?php

    require_once("../Models/Ticket.php");

    class Chatgpt extends Conectar{ #Clase Chatgpt extendida de Conectar

        public function get_respuestaia($tick_id){
            $ticket = new Ticket();
            $datos = $ticket->listar_ticket_x_id($tick_id);
            foreach ($datos as $row){
                $tick_descrip = $row["tick_descrip"];
            }
            $apikey = 'OpenAI API KEY HERE';

            $model = "gpt-3.5-turbo";

            $messages = [
                [
                    'role' => 'system',
                    'content' => 'Eres un técnico en TI.'
                ],
                [
                    'role' => 'user',
                    'content' => $tick_descrip
                ]
            ];

            $data = [
                "model" => $model, //Modelo
                "messages" => $messages,
                "temperature" => 0.5, //Que tan inteligente se necesita que sea ChatGPT
                "max_tokens" => 1024 //Maximo de paalabras que puede responder ChatGPT
            ];

            /* 
            Version Antigua de ChatGPT

            $apikey = 'API KEY HERE';


            $data = [
                'model' => 'text-davinci-002', //Modelo
                'prompt' => 'Responde como un técnico de soporte TI: '.$tick_descrip, //Pregunta
                'temperature' => 0.7, //Que tan inteligente se necesita que sea ChatGPT
                'max_tokens' => 300, //Maximo de paalabras que puede responder ChatGPT
                'n' => 1,
                'stop' => ['\n']
            ]; */

            $ch = curl_init('https://api.openai.com/v1/chat/completions');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apikey
            ));

            $response = curl_exec($ch);

            /* echo  $response; */

            $responseArr = json_decode($response, true);

            /* print($response); */

            /* return $responseArr['choices'][0]['message']; */

            return $responseArr['choices'][0]['message']['content'];
        }

    }

?>
<?php
require __DIR__ . '/../../vendor/autoload.php';

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;

$api_key = 'api_production_3bd8b962183dbc045db6e1cee2fa0c937e5c3a09f605ab391e3971452c7e7ef8.6977052f142a194b364737d3.697764295d83271de3b9945f';

$client = new \GuzzleHttp\Client();

function initTask($api_key, $client) {
    $headers = array(
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $api_key,
    );

    try {
    $response = $client->request(
        'POST',
        'https://api.freeconvert.com/v1/process/import/upload',
        array(
        'headers' => $headers,
        )
    );
        return $response->getBody()->getContents();
    }
    catch (\GuzzleHttp\Exception\BadResponseException $e) {
    // handle exception or api errors.
        print_r($e->getMessage());
    }
}

function uploadFile($upload_url, $signature, $file_path, $file_name, $client, $api_key) {
    $headers = [
        'Authorization' => 'Bearer ' . $api_key
    ];

    $options = [
    'multipart' => [
        [
            'name' => 'file',
            'contents' => Utils::tryFopen($file_path, 'r'),
            'filename' => $file_name,
            'headers'  => [
                'Content-Type' => '<Content-type header>'
            ]
        ],
        [
            'name' => 'signature',
            'contents' => $signature
        ]
    ]];

    try {
        $request = new Request(
            'POST',
            $upload_url,
            $headers
        );

        $response = $client->sendAsync($request, $options)->wait();

        if ($response->getStatusCode() != 200) {
            throw new Exception("Error uploading file: " . $response->getBody()->getContents());
        } else {
            return $response->getBody()->getContents();
        }
    }
    catch (\GuzzleHttp\Exception\BadResponseException $e) {
        // handle exception or api errors.
        print_r($e->getMessage());
    }
}

function convertFile($api_key, $client, $task_id) {
    $headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $api_key,
    ];

    // Define array of request body.
    $request_body = array(
        'input' => $task_id,
        'input_format' => 'mp4',
        'output_format' => 'webm',
    );
    
    try {
        $request = new Request(
            'POST',
            'https://api.freeconvert.com/v1/process/convert',
            $headers,
            json_encode($request_body)
        );

        $response = $client->sendAsync($request)->wait();

        return $response->getBody()->getContents();
    } catch (\GuzzleHttp\Exception\BadResponseException $e) {
        // handle exception or api errors.
        print_r($e->getMessage());
    }
}

function downloadFile($api_key, $client, $task_id) {
    $headers = array(
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $api_key,
    );

    $maxAttemps = 3;
    $attempt = 0;

    while ($attempt < $maxAttemps) {
        try {
            $request = new Request(
                'GET',
                'https://api.freeconvert.com/v1/process/tasks/' . $task_id,
                $headers
            );
        
            $response = $client->sendAsync($request)->wait();
        
            $data = json_decode($response->getBody()->getContents());
        
            $status = $data->status;
        
            if ($status === 'completed') {
                $url =  $data->result->url;

                $name = basename($url);
                if (file_put_contents(__DIR__ . '/../../public/videos/' . $name, file_get_contents($url))) {
                    return "/public/videos/" . $name;
                }
                break;
            } else if ($status === 'failed'){
                echo json_encode(["status" => "error", "message" => "La conversión ha fallado. - $data->result->errorCode"]);
                exit;
            } else {
                sleep(30);
                $attempt++;
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }
    }
}
<?php

function curlRequest($endPoint , $query) {

    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://yh-finance.p.rapidapi.com/$endPoint=$query&region=US",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-host: yh-finance.p.rapidapi.com",
        "x-rapidapi-key: f0d3e7f880msh2833404c41f6f40p1906b2jsn610b11b925a0"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $result = json_decode($response, true);
    return $result;
    
}

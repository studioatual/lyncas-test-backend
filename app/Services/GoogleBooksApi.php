<?php

namespace Lyncas\Services;

class GoogleBooksApi
{
    protected $baseURL;

    public function __construct(string $baseURL)
    {
        $this->baseURL = $baseURL;
    }

    public function request(array $data = []) 
    {
        $query = http_build_query($data);

        $headers = [
            'Accept : application/x-www-form-urlencoded; charset=UTF-8',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        ];

        $url = $this->baseURL . '?' . $query;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result);
    }
}

<?php

namespace components;

use components\Debugger as d;

class CcUrl
{
    public $base_url;
    public $auth;

    public function __construct($base_url)
    {
        $this->base_url = $base_url;
    }

    public function request($endpoint = '', $options = [], $method)
    {

        $api_endpoint = $this->base_url;
        $curl = curl_init();

        $headers = [
        ];

        if (isset($options['headers']) and count($options['headers'])) {
            $headers = array_merge($headers, $options['headers']);
        }

        if (count($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        unset($options['url_items']);
        unset($options['headers']);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options));
        } else {
            if (count($options)) {
                $api_endpoint .= '?' . http_build_query($options);
            }
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $api_endpoint);
        $response = curl_exec($curl);
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        d::pe($http_status_code);

        if ($http_status_code == 200) {

            d::pe($response);

            $decoded = json_decode($response, true);
            $data = $decoded;
        } else {
            d::pe('Ошибка');
            $data = [];
            $data['error'] = $response;
        }
        return $data;

    }

} //Class
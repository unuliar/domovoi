<?php

namespace App\Vk;
use Symfony\Component\HttpClient\HttpClient;


class Api
{
    private $httpClient;
    private $vkSecret;
    public static $CALLBACK_URL = "http://localhost:81/api/vkAuthCallback";
    public static $CLIENTID = "7508602";


    public function __construct($vkSecret)
    {
        $this->vkSecret = $vkSecret;
        $this->httpClient = HttpClient::create();
    }

    public function getAuthToken($code) {
        return $this->httpClient->request("GET", "https://oauth.vk.com/access_token", [
            'query' => [
                "client_id" => static::$CLIENTID,
                "client_secret" => $this->vkSecret,
                "redirect_uri" => static::$CALLBACK_URL,
                "code" => $code,
            ]
        ])->toArray();
    }


    public function getUserData($token) {
        return $this->httpClient->request("GET", "https://api.vk.com/method/users.get", [
            'query' => [
                "access_token" => $token,
                "fields" => "photo_100,bdate,city",
                "v" => "5.110"
            ]
        ])->toArray();
    }

}
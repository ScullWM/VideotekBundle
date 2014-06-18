<?php

namespace Swm\VideotekBundle\Adapter;

use GuzzleHttp\Client as HttpClient;

class GuzzleAdapter implements HttpAdapterInterface
{
    private $httpClient;

    private function init()
    {
        $this->httpClient = new HttpClient();
    }


    public function get($url, $path)
    {
        if(empty($this->httpClient)) $this->init();

        $response = $this->httpClient->get($url);
        $body = $response->getBody();


        var_dump($url, $body);


        return new \SplFileInfo($path);
    }
}
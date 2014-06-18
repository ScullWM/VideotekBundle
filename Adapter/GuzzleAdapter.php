<?php

namespace Swm\VideotekBundle\Adapter;

use Guzzle\HttpClient;

class GuzzleAdapter implements HttpAdapterInterface
{
    private $httpClient;

    private function init()
    {
        $this->httpClient = new HttpClient();
    }


    public function get($path)
    {
        if(empty($this->httpClient)) $this->init();
    }
}
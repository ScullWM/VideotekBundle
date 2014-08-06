<?php

namespace Swm\VideotekBundle\Adapter;

use GuzzleHttp\Stream;
use GuzzleHttp\Client;

class GuzzleAdapter implements HttpAdapterInterface
{
    private $client;

    public function get($url, $path)
    {
        $original = Stream\create(fopen($url, 'r'));
        file_put_contents($path, $original->getContents());

        return new \SplFileInfo($path);
    }

    /**
     * @param string $url
     */
    public function getJson($url)
    {
        $client = new Client();
        $res = $client->get($url);

        return (array) $res->json();
    }

    public function getCode($url)
    {
        if(empty($this->client)) {
            $this->client = new Client();
        }

        try {
            $res = $this->client->get($url);
            $code = $res->getStatusCode();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $code = 404;
        }

        return (int) $code;
    }
}
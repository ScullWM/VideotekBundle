<?php

namespace Swm\VideotekBundle\Adapter;

use GuzzleHttp\Stream;
use GuzzleHttp\Client;

class GuzzleAdapter implements HttpAdapterInterface
{
    public function get($url, $path)
    {
        $original = Stream\create(fopen($url, 'r'));
        file_put_contents($path, $original->getContents());

        return new \SplFileInfo($path);
    }

    public function getJson($url)
    {
        $client = new Client();
        $res = $client->get($url);

        return (array) $res->json();
    }

}
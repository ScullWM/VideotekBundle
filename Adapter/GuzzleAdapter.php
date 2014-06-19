<?php

namespace Swm\VideotekBundle\Adapter;

use GuzzleHttp\Stream;

class GuzzleAdapter implements HttpAdapterInterface
{
    public function get($url, $path)
    {
        $original = Stream\create(fopen($url, 'r'));
        file_put_contents($path, $original->getContents());

        return new \SplFileInfo($path);
    }
}
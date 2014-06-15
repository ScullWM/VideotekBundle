<?php

namespace Swm\VideotekBundle\Service\DistantHosting;

class PhpwayProvider implements CallProviderInterface
{
    public function getLocal($url, $filename, $path)
    {
        file_put_contents($path.'/'.$filename, file_get_contents($url));
        return new splFileInfo($path.'/'.$filename);
    }
}
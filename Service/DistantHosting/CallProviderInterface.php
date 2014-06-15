<?php

namespace Swm\VideotekBundle\Service\DistantHosting;

interface CallProviderInterface
{
    public function getLocal($url, $filename, $path);
}
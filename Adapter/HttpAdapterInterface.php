<?php

namespace Swm\VideotekBundle\Adapter;

interface HttpAdapterInterface
{
    public function get($url, $path);
}
<?php

namespace Swm\VideotekBundle\Adapter;

interface HttpAdapterInterface
{
    /**
     * @param string $path
     *
     * @return \SplFileInfo
     */
    public function get($url, $path);
}
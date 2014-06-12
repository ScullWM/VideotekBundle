<?php

namespace Swm\VideotekBundle\Service\VideoService;

interface VideoServiceInterface
{
    public function getVideoId($url);

    public function getThumbnails($id);
}
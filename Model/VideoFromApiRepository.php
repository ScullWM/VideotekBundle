<?php

namespace Swm\VideotekBundle\Model;

use Swm\VideotekBundle\Entity\Video;

class VideoFromApiRepository
{
    public function convertToEntity($data)
    {
        $video = new Video();
        $video->setTitle($data->title);
        $video->setUrl($data->url);
        $video->setDescription($data->description);
        $video->setHits(0);
        $video->setFav(true);
        $video->setStatut(true);
    }
}
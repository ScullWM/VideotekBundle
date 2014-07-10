<?php

namespace Swm\VideotekBundle\Model;

use Swm\VideotekBundle\Entity\Video;

class VideoFromApiRepository
{
    public function convertToEntity($data)
    {
        $video = new Video();
        $video->setTitle($data->getTitle());
        $video->setUrl($data->getUrl());
        $video->setDescription($data->getDescription());
        $video->setHits(0);
        $video->setFav(true);
        $video->setStatut(true);

        return $video;
    }
}
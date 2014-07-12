<?php

namespace Swm\VideotekBundle\Model;

use Swm\VideotekBundle\Entity\Video;

class VideoFromApiRepository
{
    public function convertToEntity($data)
    {
        $video = new Video();
        $video->setTitle($data->getTitle())
            ->setUrl($data->getUrl())
            ->setDescription($data->getDescription())
            ->setHits(0)
            ->setFav(true)
            ->setStatut(true)
        ;

        return $video;
    }
}
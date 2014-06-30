<?php

namespace Swm\VideotekBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Swm\VideotekBundle\Entity\Video;

class VideoEvent extends Event
{
    protected $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function getVideo()
    {
        return $this->video;
    }
}
<?php

namespace Swm\VideotekBundle\EventListener;

use Swm\VideotekBundle\Event\VideoEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoListener implements EventSubscriberInterface
{
    private $distantHostingService;

    public function __construct($distantHostingService)
    {
        $this->distantHostingService = $distantHostingService;
    }

    public function getThumb(VideoEvent $videoEvent)
    {
        $video = $videoEvent->getVideo();

        $msg = array('id' => $video->id, 'image_path' => $video->img_big);
        $this->get('old_sound_rabbit_mq.download_thumb_producer')->publish(serialize($msg));
    }

    static public function getSubscribedEvents()
    {
        return array('doctrine.event_listener'=>'postPersist');
    }
}
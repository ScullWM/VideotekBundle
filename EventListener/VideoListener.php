<?php

namespace Swm\VideotekBundle\EventListener;

use Swm\VideotekBundle\Event\VideoEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoListener implements EventSubscriberInterface
{
    private $videoService;

    public function __construct($videoService)
    {
        $this->videoService = $videoService;
    }

    public function getThumb(VideoEvent $videoEvent)
    {
        $video = $videoEvent->getVideo();
        $videoExtended = $this->videoService->getInfoFromVideo($video);

        $msg = array('id' => $videoExtended->id, 'image_path' => $videoExtended->img_big);
        $this->get('old_sound_rabbit_mq.download_thumb_producer')->publish(serialize($msg));
    }

    static public function getSubscribedEvents()
    {
        return array('doctrine.event_listener'=>'postPersist');
    }
}
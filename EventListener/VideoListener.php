<?php

namespace Swm\VideotekBundle\EventListener;

use Swm\VideotekBundle\Event\VideoEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoListener implements EventSubscriberInterface
{
    private $distanthostingservice;

    public function __construct($distanthostingservice)
    {
        $this->distanthostingservice = $distanthostingservice;
    }

    public function getThumb(VideoEvent $videoEvent)
    {
        echo'<pre>';
        print_r($videoEvent);
        echo'</pre>';
        exit();
    }

    static public function getSubscribedEvents()
    {
        return array('doctrine.event_listener'=>'postPersist');
    }
}
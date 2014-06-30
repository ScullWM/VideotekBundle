<?php

namespace Swm\VideotekBundle\EventListener;

use Swm\VideotekBundle\Entity\Video;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoListener implements EventSubscriberInterface
{
    private $distanthostingservice;

    public function __construct($distanthostingservice)
    {
        $this->distanthostingservice = $distanthostingservice;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();


        if ($entity instanceof Video)
        {
            $entity->setfav(true);
            $entity->sethits($entity->getid());
            $entityManager->persist($entity);
        }
    }

    static public function getSubscribedEvents()
    {
        return array('doctrine.event_listener'=>'postPersist');
    }
}
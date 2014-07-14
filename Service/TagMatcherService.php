<?php

namespace Swm\VideotekBundle\Service;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Entity\Tag;
use Swm\VideotekBundle\Exception;

class TagMatcherService
{
    private $entityManager;
    private $video;
    private $tags = array();

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        $this->tags = $this->entityManager->getRepository("SwmVideotekBundle:Tag")->findAll();
    }
    
    public function process()
    {
        if(!($this->video instanceof Video)) throw new VideoException("No valid Video Entity to match tags");

        array_map(array($this, 'matchTag'), $this->tags);

        $this->entityManager->persist($this->video);
        $this->entityManager->flush();
    }


    private function matchTag(Tag $tag)
    {
        if(strstr($this->video->getDescription(), $tag->getTag()))
        {
            $this->video->addTag($tag);
        }
        return;
    }

    /**
     * Sets the value of video.
     *
     * @param mixed $video the video 
     *
     * @return self
     */
    public function setVideo(Video $video)
    {
        $this->video = $video;

        return $this;
    }
}
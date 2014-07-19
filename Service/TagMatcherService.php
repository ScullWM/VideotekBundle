<?php

namespace Swm\VideotekBundle\Service;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Entity\Tag;
use Swm\VideotekBundle\Exception;
use Swm\VideotekBundle\Model\TagAliasModel;

class TagMatcherService
{
    private $entityManager;
    private $video;
    private $tags = array();
    private $tagAliasGenerator;
    private $matchedTag = 0;

    public function __construct($entityManager, $tagAliasGenerator)
    {
        $this->entityManager = $entityManager;
        $this->tagAliasGenerator = $tagAliasGenerator;
        $tags = $this->entityManager->getRepository("SwmVideotekBundle:Tag")->findAll();

        $this->tags = $this->tagAliasGenerator->process($tags);
    }
    
    public function process()
    {
        if(!($this->video instanceof Video)) throw new VideoException("No valid Video Entity to match tags");

        array_map(array($this, 'matchTag'), $this->tags);

        $this->entityManager->persist($this->video);
        $this->entityManager->flush();
    }

    public function getPertinence()
    {
        $this->matchedTag = 0;
        array_map(array($this, 'matchTag'), $this->tags);

        return (int) $this->matchedTag;
    }

    private function matchTag(TagAliasModel $tag)
    {
        if(strstr($this->video->getDescription(), $tag->getTag()) || strstr($this->video->getTitle(), $tag->getTag()))
        {
            //$this->video->addTag($tag->getOriginalTag());
            $this->matchedTag++;
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
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }
}
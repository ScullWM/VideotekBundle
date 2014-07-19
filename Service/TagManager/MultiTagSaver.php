<?php

namespace Swm\VideotekBundle\Service\TagManager;

use Swm\VideotekBundle\Entity\Tag;

class MultiTagSaver
{
    private $em;
    private $repository;
    private $addTag = 0;

    public function __construct($em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository("SwmVideotekBundle:Tag");
    }

    public function process(array $tags)
    {
        array_map(array($this, 'addTag'), $tags);

        return $this->addTag;
    }

    private function addTag($stringTag)
    {
        if($this->repository->getByTag($stringTag)) return;

        $tag = new Tag();
        $tag->setTag($stringTag);

        $this->em->persist($tag);
        $this->em->flush();

        $this->addTag++;
    }
}

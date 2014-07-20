<?php

namespace Swm\VideotekBundle\Service;

class PopulateService
{
    private $em;
    private $repository;

    public function __construct($em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository("SwmVideotekBundle:Tag");
    }

    public function getRandomTag()
    {
        $tag = $this->repository->getRandomTag();

        return $tag;
    }

    public function checkVideos(array $videos)
    {

    }

    public function isNew($video)
    {
        $url = $video->getUrl();
        $result = $this->em->getRepository('SwmVideotekBundle:Video')->getByUrl($url);

        return $result;
    }
}
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
}
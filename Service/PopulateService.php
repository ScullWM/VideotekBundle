<?php

namespace Swm\VideotekBundle\Service;

class PopulateService
{
    private $em;
    private $repository;
    private $videoScrapper;
    private $totalVideoAdded = 0;
    private $minPertinence = 25;

    public function __construct($em, $videoScrapper, $tagMatcher, $apiConverter)
    {
        $this->em = $em;
        $this->repository = $em->getRepository("SwmVideotekBundle:Tag");

        $this->videoScrapper = $videoScrapper;
        $this->tagMatcher = $tagMatcher;
        $this->apiConverter = $apiConverter;
    }

    public function search($tag, $prefix, $service)
    {
        $this->videoScrapper->setScrapperService($service);
        $videos = $this->videoScrapper->search($prefix.$tag, 100, 'date');

        return $videos;
    }

    public function machinate(array $videos)
    {
        array_map(array($this, 'checkVideo'), $videos);
    }

    private function checkVideo($video)
    {
        $basicPertinence = $this->tagMatcher->setVideo($video)->getPertinence();

        if(0 != $basicPertinence && $this->isNew($video)) {
            $videoDetail = $this->videoScrapper->seeResult($video->getVideoid());
            $pertinence  = $this->tagMatcher->setVideo($videoDetail)->getPertinence();

            if($this->minPertinence <= $pertinence)
            {
                $video = $this->apiConverter->convertToEntity($videoDetail);

                $this->em->persist($video);
                $this->em->flush();

                $this->totalVideoAdded++;
                $this->output->writeln('New video! <fg=green>'.$video->getTitle().'</fg=green>');
                $this->output->writeln('Pertinence: '.$basicPertinence.'=><fg=green>'.$pertinence.'</fg=green>');
            }
        }else {
            $this->output->writeln('Video: '.$basicPertinence);
        }
    }

    public function getVideosAdded()
    {
        return $this->totalVideoAdded;
    }

    public function addOutput($output)
    {
        $this->output = $output;
    }

    public function getRandomTag()
    {
        $tag = $this->repository->getRandomTag();

        return $tag;
    }

    public function isNew($video)
    {
        $url = $video->getUrl();
        $result = $this->em->getRepository('SwmVideotekBundle:Video')->getDoublon($url);

        return (0 === $result);
    }
}
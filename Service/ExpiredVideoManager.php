<?php

namespace Swm\VideotekBundle\Service;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swm\VideotekBundle\Entity\Video;

class ExpiredVideoManager
{
    private $doctrine;
    private $distantHosting;
    private $videoService;

    public function __construct($doctrine, $distantHosting, $videoService)
    {
        $this->doctrine = $doctrine;
        $this->distantHosting = $distantHosting;
        $this->videoService = $videoService;
    }

    public function process(InputInterface $input, OutputInterface $output)
    {
        $videos = $this->doctrine->getRepository("SwmVideotekBundle:Video")->findAll();

        $outputVideo = array_map(array($this, 'checkVideo'), $videos);
        $outputVideo = array_filter($outputVideo);

        foreach ($outputVideo as $msg) {
            $output->writeln($msg);
        }
        $output->writeln('-----------------');
        $output->writeln('Work Done: '.count($outputVideo).' video(s)');
    }

    private function checkVideo(Video $video)
    {
        $id_video = $video->getId();
        $videoExtended = $this->videoService->getInfoFromVideo($video);
        $expired = $this->distantHosting->isExpired($videoExtended);

        if($expired===false) return;

        $this->em->remove($video);
        $this->em->flush();

        return (string) '<fg=red>Video '.$id_video.' removed</fg=red>';
    }
}
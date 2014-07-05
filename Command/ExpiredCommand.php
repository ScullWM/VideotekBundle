<?php

namespace Swm\VideotekBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swm\VideotekBundle\Entity\Video;

class ExpiredCommand extends ContainerAwareCommand
{
    private $distantService;
    private $videoservice;
    private $em;

    protected function configure()
    {
        $this
            ->setName('swm:videotek:expired')
            ->setDescription('Delete all expired video form database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $this->em = $container->get('doctrine')->getManager();
        $videos = $this->em->getRepository("SwmVideotekBundle:Video")->findAll();

        $this->distantService = $container->get('swm_videotek.distanthostingservice');
        $this->videoservice  = $container->get('swm_videotek.videoservice');

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
        $videoExtended = $this->videoservice->getInfoFromVideo($video);
        $expired = $this->distantService->isExpired($videoExtended);

        if($expired===false) return;

        $this->em->remove($video);
        $this->em->flush();

        return (string) '<fg=red>Video '.$id_video.' removed</fg=red>';
    }
}
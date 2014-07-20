<?php

namespace Swm\VideotekBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swm\VideotekBundle\Entity\Video;
use Symfony\Component\Console\Helper\ProgressHelper;

class DoubleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swm:videotek:double')
            ->setDescription('Delete all expired video form database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $videoRepo = $em->getRepository("SwmVideotekBundle:Video");

        $videos = $videoRepo->findAll();

        foreach ($videos as $video) {
            $nbre = $videoRepo->getDoublon($video->getUrl());

            if(1 === $nbre) continue;

            $doublons = $videoRepo->getByUrl($video->getUrl());

            foreach ($doublons as $dble) {
                if($video->getId() != $dble->getId()) {
                    $em->remove($dble);
                    $em->flush();

                    $output->writeln('Deleted video');
                }
            }
        }
    }
}
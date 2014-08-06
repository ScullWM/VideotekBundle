<?php

namespace Swm\VideotekBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swm\VideotekBundle\Entity\Video;
use Symfony\Component\Console\Helper\ProgressHelper;

class PromoteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swm:videotek:promote')
            ->setDescription('Set fav to a random video')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $videoRepo = $em->getRepository("SwmVideotekBundle:Video");

        $video = $videoRepo->getRandomVideo();

        $video->setFav(true);
        $em->persist($video);
        $em->flush();

        $output->writeln('<info>√</info> Done');
    }
}
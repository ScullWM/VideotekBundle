<?php

namespace Swm\VideotekBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swm\VideotekBundle\Entity\Video;
use Symfony\Component\Console\Helper\ProgressHelper;

class TagMatcherCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swm:videotek:match')
            ->setDescription('Delete all expired video form database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progress = $this->getHelperSet()->get('progress');

        $tagMatcherService = $this->getContainer()->get('swm_videotek.tag.matcher');
        $videos = $this->getContainer()->get('doctrine')->getManager()->getRepository('SwmVideotekBundle:Video')->findAll();

        $progress->start($output, count($videos));

        foreach ($videos as $video) {
            $tagMatcherService->setVideo($video)->process();
            $progress->advance();
        }

        $progress->finish();
    }
}
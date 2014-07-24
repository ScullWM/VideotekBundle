<?php

namespace Swm\VideotekBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swm\VideotekBundle\Entity\Video;
use Symfony\Component\Console\Helper\ProgressHelper;
use Swm\VideotekBundle\Model\VideoFromApiRepository;

class PopulateCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this
            ->setName('swm:videotek:populate')
            ->setDescription('Populate video database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progress = $this->getHelperSet()->get('progress');

        $populateService = $this->getContainer()->get('swm_videotek.cmd.populate');
        $tag = $populateService->getRandomTag();

        $output->writeln('Random tag: '.$tag.'');
        $output->writeln('-----------------');

        $populateService->addOutput($output);

        $videos = $populateService->search($tag, 'aircraft ', 'y');
        $populateService->machinate($videos);

        $addedVideos = $populateService->getVideosAdded();

        $output->writeln('-----------------');
        $output->writeln($addedVideos.' new video');
        //var_dump($videos);
    }
}
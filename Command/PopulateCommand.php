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
    private $minPertinence = 35;
    private $totalVideoAdded = 0;

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

        $populateExpired = $this->getContainer()->get('swm_videotek.cmd.populate');
        $tag = $populateExpired->getRandomTag();

        $output->writeln('Random tag: '.$tag.'');
        $output->writeln('-----------------');

        $videoScrapper = $this->getContainer()->get('swm_videotek.videoscrapper');
        $videoScrapper->setScrapperService('y');
        $videos = $videoScrapper->search($tag->getTag());

        $tagMatcherService = $this->getContainer()->get('swm_videotek.tag.matcher');

        $em    = $this->getContainer()->get('doctrine')->getManager();
        foreach ($videos as $video) {
            $basicPertinence = $tagMatcherService->setVideo($video)->getPertinence();
            if(0 != $basicPertinence) {
                $videoDetail = $videoScrapper->seeResult($video->getVideoid());
                $pertinence  = $tagMatcherService->setVideo($videoDetail)->getPertinence();

                if($this->minPertinence <= $pertinence)
                {
                    $VideoApiService = new VideoFromApiRepository();
                    $video = $VideoApiService->convertToEntity($videoDetail);

                    $em->persist($video);
                    $em->flush();

                    $this->totalVideoAdded++;
                    $output->writeln('New video! <fg=green>'.$video->getTitle().'</fg=green>');
                    $output->writeln('Pertinence: '.$basicPertinence.'=><fg=green>'.$pertinence.'</fg=green>');
                }
            }

        }
        $output->writeln('-----------------');
        $output->writeln($this->totalVideoAdded.' new video');
        //var_dump($videos);
    }
}
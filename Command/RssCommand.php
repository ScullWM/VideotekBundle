<?php

namespace Swm\VideotekBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swm\VideotekBundle\Entity\Video;
use Symfony\Component\Console\Helper\ProgressHelper;

class RssCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swm:videotek:rss')
            ->setDescription('Generate rss feed with random videos')
            ->addOption('filename',  null, InputOption::VALUE_REQUIRED, 'Defines feed filename')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getOption('filename');

        $em = $this->getContainer()->get('doctrine')->getManager();
        $video = $em->getRepository("SwmVideotekBundle:Video")->getRandomVideo();

        $videoservice  = $this->getContainer()->get('swm_videotek.videoservice');
        $videoExtended = $videoservice->getInfoFromVideo($video);

        $feed = $this->getContainer()->get('eko_feed.feed.manager')->get('videos');
        $feed->addFromArray(array($videoExtended));

        $dump = $feed->render('rss');
        $filepath = $this->getWebPath() . $filename;

        $this->getFilesystem()->dumpFile($filepath, $dump);


        $output->writeln('<comment>done!</comment>');
        $output->writeln(sprintf('<info>Feed has been dumped and located in "%s"</info>', $filepath));
    }

    /**
     * Get Symfony web path
     *
     * @return mixed
     */
    protected function getWebPath()
    {
        return $this->getContainer()->get('kernel')->getRootDir().'/../web/';
    }

    /**
     * Get Symfony Filesystem component
     *
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function getFilesystem()
    {
        return $this->getContainer()->get('filesystem');
    }
}
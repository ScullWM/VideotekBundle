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
            ->addOption('name',      null, InputOption::VALUE_REQUIRED, 'Feed name defined in eko_feed configuration')
            ->addOption('entity',    null, InputOption::VALUE_OPTIONAL, 'Entity to use to generate the feed')
            ->addOption('filename',  null, InputOption::VALUE_REQUIRED, 'Defines feed filename')
            ->addOption('orderBy',   null, InputOption::VALUE_OPTIONAL, 'Order field to sort by using findBy() method')
            ->addOption('direction', null, InputOption::VALUE_OPTIONAL, 'Direction to give to sort field with findBy() method')
            ->addOption('format',    null, InputOption::VALUE_OPTIONAL, 'Formatter to use to generate, "rss" is default')
            ->addOption('limit',     null, InputOption::VALUE_OPTIONAL, 'Defines a limit of entity items to retrieve')
            ->addArgument('host', InputArgument::REQUIRED, 'Set the host');
                ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name      = $input->getOption('name');
        $entity    = $input->getOption('entity');
        $filename  = $input->getOption('filename');
        $format    = $input->getOption('format') ?: 'rss';
        $limit     = $input->getOption('limit');
        $direction = $input->getOption('direction');
        $orderBy   = $input->getOption('orderBy');
        $rootDir   = $this->getContainer()->get('kernel')->getRootDir();

        $em = $this->getContainer()->get('doctrine')->getManager();
        $videos = $em->getRepository("SwmVideotekBundle:Video")->getRandomVideo();

        $videoservice  = $this->getContainer()->get('swm_videotek.videoservice');
        $videoExtended = array($videoservice->getInfoFromVideo($videos));

        $this->getContainer()->get('router')->getContext()->setHost($input->getArgument('host'));

        $feedDumpService = $this->getContainer()->get('eko_feed.feed.dump');
        $feedDumpService
                ->setName($name)
                ->setEntity($entity)
                ->setItems($videoExtended)
                ->setFilename($filename)
                ->setFormat($format)
                ->setLimit($limit)
                ->setRootDir($rootDir)
                ->setDirection($direction)
                ->setOrderBy($orderBy)
            ;

        $feedDumpService->dump();

        $output->writeln('<comment>done!</comment>');
        $output->writeln(sprintf('<info>Feed has been dumped and located in "%s"</info>', $rootDir . $filename));

    }
}
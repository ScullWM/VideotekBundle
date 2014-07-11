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

        $serviceExpired = $container->get('swm_videotek.video.expired');

        $serviceExpired->process($input, $output);
    }
}
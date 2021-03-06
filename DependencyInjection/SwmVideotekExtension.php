<?php

namespace Swm\VideotekBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SwmVideotekExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('swm_videotek.path.thumbnails', $config['path']['thumbnails']);
        $container->setParameter('swm_videotek.keys.youtubekey', $config['keys']['youtubekey']);
        $container->setParameter('swm_videotek.keys.dailymotionkey', $config['keys']['dailymotionkey']);
        $container->setParameter('swm_videotek.keys.vimeokey', $config['keys']['vimeokey']);
    }
}

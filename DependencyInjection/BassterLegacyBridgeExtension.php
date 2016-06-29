<?php

namespace Basster\LegacyBridgeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BassterLegacyBridgeExtension extends ConfigurableExtension
{
    /** {@inheritdoc} */
    protected function loadInternal(
      array $mergedConfig,
      ContainerBuilder $container
    ) {
        $container->getParameterBag()
                  ->set(
                    'basster_legacy_bridge.legacy_path',
                    $mergedConfig['legacy_path']
                  )
        ;
        $container->getParameterBag()
                  ->set(
                    'basster_legacy_bridge.prepend_script',
                    $mergedConfig['prepend_script']
                  )
        ;
        $container->getParameterBag()
                  ->set(
                    'basster_legacy_bridge.append_script',
                    $mergedConfig['append_script']
                  )
        ;

        $loader = new Loader\XmlFileLoader(
          $container,
          new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.xml');
    }
}

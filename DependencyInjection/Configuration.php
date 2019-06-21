<?php

namespace Basster\LegacyBridgeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /** {@inheritdoc} */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('basster_legacy_bridge');
        $rootNode = method_exists($treeBuilder, 'getRootNode') 
            ? $treeBuilder->getRootNode() 
            : $treeBuilder->root('basster_legacy_bridge');
        
        $rootNode
          ->children()
          ->scalarNode('legacy_path')
          ->defaultValue('/path/to/my/legacy/project/files')
          ->end()
          ->scalarNode('append_script')
          ->info('optional append script (see http://php.net/manual/en/ini.core.php#ini.auto-append-file)')
          ->defaultNull()
          ->end()
          ->scalarNode('prepend_script')
          ->info('optional prepend script (see http://php.net/manual/en/ini.core.php#ini.auto-prepend-file)')
          ->defaultNull()
          ->end()
          ->end()
        ;

        return $treeBuilder;
    }
}

<?php

namespace Basster\LegacyBridgeBundle\Tests\DependencyInjection;

use Basster\LegacyBridgeBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ArrayNode;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ArrayNode */
    private $tree;

    /**
     * @test
     */
    public function hasRootName()
    {
        self::assertEquals('basster_legacy_bridge', $this->tree->getName());
    }

    /**
     * @test
     */
    public function hasLegacyPathNodeWithDefaultValue()
    {
        $legacyPathNode = $this->getChildNode('legacy_path');

        self::assertEquals(
          '/path/to/my/legacy/project/files',
          $legacyPathNode->getDefaultValue()
        );
    }

    /**
     * @param $nodeName
     *
     * @return \Symfony\Component\Config\Definition\ArrayNode
     */
    private function getChildNode($nodeName)
    {
        /** @var ArrayNode[] $childs */
        $childs = $this->tree->getChildren();

        self::assertArrayHasKey($nodeName, $childs);

        return $childs[$nodeName];
    }

    /**
     * @test
     * @dataProvider provideNullDefaultNodes
     */
    public function hasAppendScriptNodeWithDefaultNull($nodeName)
    {
        $appendScriptNode = $this->getChildNode($nodeName);

        self::assertNull($appendScriptNode->getDefaultValue());
    }

    public function provideNullDefaultNodes()
    {
        return array(
          'append_script'  => array('append_script'),
          'prepend_script' => array('prepend_script'),
        );
    }

    protected function setUp()
    {
        $config      = new Configuration();
        $treeBuilder = $config->getConfigTreeBuilder();
        $this->tree  = $treeBuilder->buildTree();
    }
}

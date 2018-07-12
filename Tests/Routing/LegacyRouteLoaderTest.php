<?php

namespace Basster\LegacyBridgeBundle\Tests\Routing;

use Basster\LegacyBridgeBundle\Routing\LegacyRouteLoader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

class LegacyRouteLoaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var  vfsStreamFile */
    private $legacyFile;

    /** @var  LegacyRouteLoader */
    private $routeLoader;

    /** @var \org\bovigo\vfs\vfsStreamDirectory */
    private $root;

    /**
     * @test
     */
    public function createsHelloPhpRoute()
    {
        $routes = $this->routeLoader->load('.', 'legacy');

        $legacyRoute = $routes->get('basster.legacy.hello');
        $routePath   = '/hello.php';

        self::assertNotNull($legacyRoute);
        self::assertEquals($routePath, $legacyRoute->getPath());
        self::assertEquals($this->legacyFile->url(),
                           $legacyRoute->getDefault('legacyScript'));
        self::assertEquals($routePath, $legacyRoute->getDefault('requestPath'));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Do not add the "legacy" loader twice
     */
    public function shouldThrowRuntimeExceptionWhenLoadedMultipleTimes()
    {
        $this->routeLoader->load('.', 'legacy');
        $this->routeLoader->load('.', 'legacy');
    }

    /**
     * @test
     */
    public function dontSupportWhenPathIsNotExisting()
    {
        $this->routeLoader = new LegacyRouteLoader(__DIR__.'/not_existing');
        self::assertFalse($this->routeLoader->supports('any', 'legacy'));
    }

    /**
     * @test
     */
    public function dontSupportWhenPathIsNotReadable()
    {
        $dir = vfsStream::newDirectory('legacy', 0000)->at($this->root);

        $this->routeLoader = new LegacyRouteLoader($dir->url());
        self::assertFalse($this->routeLoader->supports('any', 'legacy'));
    }

    /**
     * @test
     */
    public function supportsLegacyType()
    {
        self::assertTrue(
          $this->routeLoader->supports("don't mind...", 'legacy')
        );
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        $this->root       = vfsStream::setup();
        $this->legacyFile  = vfsStream::newFile('hello.php')->at($this->root);
        $this->routeLoader = new LegacyRouteLoader($this->root->url());
    }
}

<?php

namespace Basster\LegacyBridgeBundle\Tests\Controller;

use Basster\LegacyBridgeBundle\Controller\LegacyScriptController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Finder\SplFileInfo;

class LegacyScriptControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Container */
    private $container;

    /** @var  LegacyScriptController */
    private $controller;

    /** @var  \Symfony\Component\Finder\SplFileInfo */
    private $legacyFile;

    /** @var  string */
    private $legacyScript;

    /**
     * @test
     */
    public function phpSelfShouldBeRequestPath()
    {
        $this->doRunLegacyScript();

        self::assertEquals(__FILE__, $_SERVER['PHP_SELF']);
        self::assertEquals(__FILE__, $_SERVER['SCRIPT_NAME']);
        self::assertEquals($this->legacyScript, $_SERVER['SCRIPT_FILENAME']);
        self::assertEquals($this->container, $_SERVER['SYMFONY_CONTAINER']);
    }

    private function doRunLegacyScript()
    {
        $streamedResponse = $this->controller->runLegacyScript(
          $this->legacyFile->getPathname(),
          $this->legacyFile->getRelativePathname()
        );

        self::assertInstanceOf(
          'Symfony\Component\HttpFoundation\StreamedResponse',
          $streamedResponse
        );

        $streamedResponse->sendContent();
    }

    /**
     * @test
     */
    public function shouldRunPrependSriptIfGiven()
    {
        $this->controller->setPrependScript(__DIR__ . '/_files/prepend.php');

        $this->doRunLegacyScript();

        self::assertArrayHasKey('prepend_something', $_SERVER);
        self::assertEquals('something', $_SERVER['prepend_something']);
    }

    /**
     * @test
     */
    public function shouldRunAppendScriptIfGiven()
    {
        $this->controller->setAppendScript(__DIR__ . '/_files/append.php');

        $this->doRunLegacyScript();

        self::assertArrayHasKey('append_foo', $_SERVER);
        self::assertEquals('foo', $_SERVER['append_foo']);

    }

    protected function setUp()
    {
        $this->legacyScript = __DIR__ . '/_files/hello.php';
        $this->legacyFile   = $this->getLegacyFile();

        $this->container = new Container();

        $this->controller = new LegacyScriptController();
        $this->controller->setContainer($this->container);
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    private function getLegacyFile()
    {
        return new SplFileInfo(__FILE__, __DIR__, $this->legacyScript);
    }
}

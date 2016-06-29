<?php

namespace Basster\LegacyBridgeBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class LegacyScriptController
 *
 * @package Basster\LegacyBridgeBundle\Controller
 */
class LegacyScriptController implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /** @var  string */
    private $prependScript;

    /** @var  string */
    private $appendScript;

    /**
     * @param $requestPath
     * @param $legacyScript
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function runLegacyScript($requestPath, $legacyScript)
    {
        $container = $this->container;
        $prepend   = $this->prependScript;
        $append    = $this->appendScript;

        $requireLegacyScript = function () use (
          $requestPath,
          $legacyScript,
          $container,
          $prepend,
          $append
        ) {
            $_SERVER['PHP_SELF']          = $requestPath;
            $_SERVER['SCRIPT_NAME']       = $requestPath;
            $_SERVER['SCRIPT_FILENAME']   = $legacyScript;
            $_SERVER['SYMFONY_CONTAINER'] = $container;
            chdir(dirname($legacyScript));

            if ($prepend) {
                /** @noinspection PhpIncludeInspection */
                require $prepend;
            }

            /** @noinspection PhpIncludeInspection */
            require $legacyScript;

            if ($append) {
                /** @noinspection PhpIncludeInspection */
                require $append;
            }
        };

        return StreamedResponse::create($requireLegacyScript);
    }

    /**
     * @param string|null $script
     */
    public function setPrependScript($script = null)
    {
        $this->prependScript = $script;
    }

    /**
     * @param string|null $script
     */
    public function setAppendScript($script = null)
    {
        $this->appendScript = $script;
    }

    /** {@inheritdoc} */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}

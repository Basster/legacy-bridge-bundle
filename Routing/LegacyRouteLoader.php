<?php

namespace Basster\LegacyBridgeBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class LegacyRouteLoader extends Loader
{
    /** @var bool */
    private $loaded = false;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    private $finder;

    /**
     * @var string
     */
    private $legacyPath;

    /**
     * LegacyRouteLoader constructor.
     *
     * @param string                                $legacyPath
     * @param \Symfony\Component\Finder\Finder|null $finder
     */
    public function __construct($legacyPath, Finder $finder = null)
    {
        $this->finder     = $finder ?: new Finder();
        $this->legacyPath = $legacyPath;
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "legacy" loader twice');
        }

        $routes = new RouteCollection();
        $this->initFinder();

        $defaults = array(
          '_controller' => 'basster_legacy_bridge.legacy_controller:runLegacyScript',
        );

        /** @var SplFileInfo $file */
        foreach ($this->finder as $file) {
            $defaults['legacyScript'] = $file->getPathname();
            $defaults['requestPath']  = '/'.$file->getRelativePathname();

            $route = new Route($file->getRelativePathname(), $defaults);
            $routes->add($this->createLegacyRouteName($file), $route);
        }

        $this->loaded = true;

        return $routes;
    }


    /** {@inheritdoc} */
    public function supports($resource, $type = null)
    {
        return 'legacy' === $type && \is_dir($this->legacyPath) && \is_readable($this->legacyPath);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function initFinder()
    {
        $this->finder->ignoreDotFiles(true)
                     ->files()
                     ->name('*.php')
                     ->in($this->legacyPath);
    }
    /**
     * @param SplFileInfo $file
     *
     * @return string
     */
    private function createLegacyRouteName(SplFileInfo $file)
    {
        return 'basster.legacy.'.
          str_replace(
            '/',
            '__',
            substr($file->getRelativePathname(), 0, -4)
          );
    }
}

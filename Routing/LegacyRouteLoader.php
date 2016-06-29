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

    /** @var  string */
    private $legacyPath;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    private $finder;

    /**
     * LegacyRouteLoader constructor.
     *
     * @param string $legacyPath
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($legacyPath, Finder $finder = null)
    {
        $this->legacyPath = $legacyPath;

        $this->finder = $finder ?: new Finder();
        $this->finder->ignoreDotFiles(true)
                     ->files()
                     ->name('*.php')
                     ->in($legacyPath)
        ;
    }

    /** {@inheritdoc} */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "legacy" loader twice');
        }

        $routes = new RouteCollection();

        $defaults = array(
          '_controller' => 'basster_legacy_bridge.legacy_controller:runLegacyScript',
        );

        /** @var SplFileInfo $file */
        foreach ($this->finder as $file) {
            $defaults['legacyScript'] = $file->getPathname();
            $defaults['requestPath']  = '/' . $file->getRelativePathname();

            $route = new Route($file->getRelativePathname(), $defaults);
            $routes->add($this->createLegacyRouteName($file), $route);
        }

        $this->loaded = true;

        return $routes;
    }

    /**
     * @param SplFileInfo $file
     *
     * @return string
     */
    private function createLegacyRouteName(SplFileInfo $file)
    {
        return 'basster.legacy.' .
        str_replace(
          '/',
          '__',
          substr($file->getRelativePathname(), 0, -4)
        );
    }

    /** {@inheritdoc} */
    public function supports($resource, $type = null)
    {
        return 'legacy' === $type;
    }
}

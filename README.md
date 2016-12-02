BassterLegacyBridgeBundle
=========================

[![Build Status](https://travis-ci.org/Basster/legacy-bridge-bundle.svg?branch=master)](https://travis-ci.org/Basster/legacy-bridge-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Basster/legacy-bridge-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Basster/legacy-bridge-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Basster/legacy-bridge-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Basster/legacy-bridge-bundle/?branch=master)

What is this?
-------------

This bundle generates a symfony route from each .php file on the given `legacy_path` folder, so you can access your old scripts through the symfony frontcontroller as they where actually present.
Additionally the wrapper injects the symfony DI-Container into `$_SERVER['SYMFONY_CONTAINER']`, so you can slowly refactor the legacy app, by extracting services into symfony services but
use them in the legacy code, as well.

Inspired from [Modernizing with Symfony](https://slidr.io/derrabus/modernizing-with-symfony) given by [@derrabus](https://twitter.com/derrabus) 

Installation
------------

    composer require basster/legacy-bridge-bundle

Configuration
-------------
In your config.yml place:

    basster_legacy_bridge:
        legacy_path:    '/full/path/to/my/legacy/project/files'
        # optional prepend script (see http://php.net/manual/en/ini.core.php#ini.auto-prepend-file)
        prepend_script: '/full/path/to/my/legacy/autoPrependFile.php' # can be ommited
        # optional append script (see http://php.net/manual/en/ini.core.php#ini.auto-append-file)
        append_script:  '/full/path/to/my/legacy/autoAppendFile.php' # can be ommited

On the legacy app
-----------------

    <?php // e.g. my-old-stuff.php
    /** @var \Symfony\Component\DependencyInjection\ContainerInterface $container */
    $container = $_SERVER['SYMFONY_CONTAINER'];
    $myService = $container->get('my.service.id');

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

BassterLegacyBridgeBundle
=========================

[![Build Status](https://travis-ci.org/Basster/legacy-bridge-bundle.svg?branch=master)](https://travis-ci.org/Basster/legacy-bridge-bundle)[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Basster/legacy-bridge-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Basster/legacy-bridge-bundle/?branch=master)[![Code Coverage](https://scrutinizer-ci.com/g/Basster/legacy-bridge-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Basster/legacy-bridge-bundle/?branch=master)

Inspired from: https://slidr.io/derrabus/modernisieren-mit-symfony (in german)

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
    

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

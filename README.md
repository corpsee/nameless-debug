[![PHP Censor](http://ci.php-censor.info/build-status/image/5?branch=master&label=PHPCensor&style=flat-square)](http://ci.php-censor.info/build-status/view/5?branch=master)
[![Travis CI](https://img.shields.io/travis/corpsee/nameless-debug/master.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/corpsee/nameless-debug?branch=master)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/c9cec137-2be1-4e94-86dd-bd530952a9b8.svg?label=Insight&style=flat-square)](https://insight.sensiolabs.com/projects/c9cec137-2be1-4e94-86dd-bd530952a9b8)
[![Codecov](https://img.shields.io/codecov/c/github/corpsee/nameless-debug.svg?label=Codecov&style=flat-square)](https://codecov.io/gh/corpsee/nameless-debug)
[![Latest Version](https://img.shields.io/packagist/v/corpsee/nameless-debug.svg?label=Version&style=flat-square)](https://packagist.org/packages/corpsee/nameless-debug)
[![Total downloads](https://img.shields.io/packagist/dt/corpsee/nameless-debug.svg?label=Downloads&style=flat-square)](https://packagist.org/packages/corpsee/nameless-debug)
[![License](https://img.shields.io/packagist/l/corpsee/nameless-debug.svg?label=License&style=flat-square)](https://packagist.org/packages/corpsee/nameless-debug)

**This package is abandoned and no longer maintained. The author suggests using the 
[symfony/debug](https://github.com/symfony/debug) package instead.**

Nameless debug
==============

Simple and independent PHP debug component compliant with PSR-1, PSR-2, PSR-4 and Composer.

Installation
------------

You can install Nameless debug by composer. Add following code to "require" section of the `composer.json`:

```json
"require": {
    "corpsee/nameless-debug": "<version>"
}
```

And install dependencies using the Composer:

```bash
cd path/to/your-project
composer install
```

Usage
-----

Example of a simple way to use:

```php
error_reporting(-1);

use Nameless\Debug\ErrorHandler;
use Nameless\Debug\ExceptionHandler;

$exception_handler = null;
if ($debug) {
    $exception_handler = (new ExceptionHandler())->register();
} else {
    set_exception_handler(function(\Exception $exception) {
        exit('Server error');
    });
}
$error_handler = (new ErrorHandler($exception_handler, $logger))->register();
```

Tests
-----

You can run the unit tests with the following commands:

```bash
cd path/to/nameless-debug
./vendor/bin/phpunit
```

License
-------

The Nameless debug is open source software licensed under the GPL-3.0 license.

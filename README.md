[![PHPCI](http://phpci.corpsee.com/build-status/image/4?branch=master&label=PHPCI&style=flat-square)](http://phpci.corpsee.com/build-status/view/4?branch=master)
[![Travis](https://img.shields.io/travis/corpsee/nameless-debug/master.svg?label=Travis&style=flat-square)](https://travis-ci.org/corpsee/nameless-debug?branch=master)
[![Latest Version](https://img.shields.io/packagist/v/corpsee/nameless-debug.svg?label=Version&style=flat-square)](https://packagist.org/packages/corpsee/nameless-debug)
[![Total downloads](https://img.shields.io/packagist/dt/corpsee/nameless-debug.svg?label=Downloads&style=flat-square)](https://packagist.org/packages/corpsee/nameless-debug)
[![License](https://img.shields.io/packagist/l/corpsee/nameless-debug.svg?label=License&style=flat-square)](https://packagist.org/packages/corpsee/nameless-debug)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/c9cec137-2be1-4e94-86dd-bd530952a9b8.svg?label=Insight&style=flat-square)](https://insight.sensiolabs.com/projects/c9cec137-2be1-4e94-86dd-bd530952a9b8)

Nameless debug package
======================

Simple and independent debug component compliant with PSR-1, PSR-2 and PSR-4 for PHP 5.4+.

Installation
------------

You can install Nameless debug package by composer. Add following code to "require" section of the composer.json:

```javascript
"require": {
    "corpsee/nameless-debug": "1.*"
}
```

And install dependencies using the Composer:

```bash
cd path/to/your-project
php composer.phar install
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

You can run the unit tests with the following command:

```bash
cd path/to/nameless-debug
./path/to/phpunit -c ./phpunit.xml
```

License
-------

The Nameless debug package is open source software licensed under the GPL-3.0 license.

[![Build Status](http://pci.corpsee.com/build-status/image/4?branch=master)](http://pci.corpsee.com/build-status/view/4?branch=master)
[![Build Status](https://travis-ci.org/corpsee/nameless-debug.svg?branch=master)](https://travis-ci.org/corpsee/nameless-debug)
[![Latest Stable Version](https://poser.pugx.org/corpsee/nameless-debug/v/stable.svg)](https://packagist.org/packages/corpsee/nameless-debug)
[![Latest Unstable Version](https://poser.pugx.org/corpsee/nameless-debug/v/unstable.svg)](https://packagist.org/packages/corpsee/nameless-debug)
[![Total Downloads](https://poser.pugx.org/corpsee/nameless-debug/downloads.svg)](https://packagist.org/packages/corpsee/nameless-debug)
[![License](https://poser.pugx.org/corpsee/nameless-debug/license.svg)](https://packagist.org/packages/corpsee/nameless-debug)

Nameless debug package
======================

Simple and independent debug component compliant with PSR1, PSR2 and PSR4 for PHP 5.4+.

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
php ./path/to/composer.phar install
```

Usage
-----

Example of a simple way to use:

```php
error_reporting(-1);

use Nameless\Debug\ErrorHandler;
use Nameless\Debug\ExceptionHandler;

$error_handler = (new ErrorHandler($logger))->register();
if ($debug) {
    $exception_handler = (new ExceptionHandler())->register();
}
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

The Nameless debug package is open source software licensed under the GPLv3 license.




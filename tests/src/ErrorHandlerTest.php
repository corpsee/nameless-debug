<?php

namespace Nameless\Debug\Tests;

use Nameless\Debug\ErrorHandler;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorHandlerNotice()
    {
        (new ErrorHandler())->register();
        try {
            $this->throwNotice();
        } catch (\ErrorException $exception) {
            self::assertEquals(E_NOTICE, $exception->getSeverity());
            self::assertEquals(__FILE__, $exception->getFile());
            self::assertRegExp('/^E_NOTICE:/', $exception->getMessage());
        }
    }

    public function testErrorHandlerWarning()
    {
        (new ErrorHandler())->register();
        try {
            $this->throwWarning();
        } catch (\ErrorException $exception) {
            self::assertEquals(E_WARNING, $exception->getSeverity());
            self::assertEquals(__FILE__, $exception->getFile());
            self::assertRegExp('/^E_WARNING:/', $exception->getMessage());
        }
    }

    public function testErrorHandlerDeprecated()
    {
        (new ErrorHandler())->register();
        try {
            $this->throwDeprecated();
        } catch (\ErrorException $exception) {
            self::assertEquals(E_DEPRECATED, $exception->getSeverity());
            self::assertEquals(__FILE__, $exception->getFile());
            self::assertRegExp('/^E_DEPRECATED:/', $exception->getMessage());
        }
    }

    public function testErrorHandlerStrict()
    {
        (new ErrorHandler())->register();
        try {
            $this->throwStrict();
        } catch (\ErrorException $exception) {
            self::assertEquals(E_STRICT, $exception->getSeverity());
            self::assertEquals(__FILE__, $exception->getFile());
            self::assertRegExp('/^E_STRICT:/', $exception->getMessage());
        }
    }

    private function throwNotice()
    {
        echo $undefined;
    }

    private function throwWarning()
    {
        array_key_exists('key', null);
    }

    private function throwDeprecated()
    {
        split('[/.-]', "06/11/2014");
    }

    private function throwStrict()
    {
        self::testErrorHandlerNotice();
    }
}

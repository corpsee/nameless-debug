<?php

namespace Nameless\Debug\Tests;

use Nameless\Debug\ErrorHandler;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorHandlerNotice()
    {
        $error_handler = (new ErrorHandler())->register();
        try {
            $this->throwNotice();
        } catch (\ErrorException $exception) {
            $this->assertEquals(E_NOTICE, $exception->getSeverity());
            $this->assertEquals(__FILE__, $exception->getFile());
            $this->assertRegexp('/^E_NOTICE:/', $exception->getMessage());
        }
    }

    public function testErrorHandlerWarning()
    {
        $error_handler = (new ErrorHandler())->register();
        try {
            $this->throwWarning();
        } catch (\ErrorException $exception) {
            $this->assertEquals(E_WARNING, $exception->getSeverity());
            $this->assertEquals(__FILE__, $exception->getFile());
            $this->assertRegexp('/^E_WARNING:/', $exception->getMessage());
        }
    }

    public function testErrorHandlerDeprecated()
    {
        $error_handler = (new ErrorHandler())->register();
        try {
            $this->throwDeprecated();
        } catch (\ErrorException $exception) {
            $this->assertEquals(E_DEPRECATED, $exception->getSeverity());
            $this->assertEquals(__FILE__, $exception->getFile());
            $this->assertRegexp('/^E_DEPRECATED:/', $exception->getMessage());
        }
    }

    public function testErrorHandlerStrict()
    {
        $error_handler = (new ErrorHandler())->register();
        try {
            $this->throwStrict();
        } catch (\ErrorException $exception) {
            $this->assertEquals(E_STRICT, $exception->getSeverity());
            $this->assertEquals(__FILE__, $exception->getFile());
            $this->assertRegexp('/^E_STRICT:/', $exception->getMessage());
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
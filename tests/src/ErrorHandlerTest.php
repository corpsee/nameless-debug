<?php

namespace Nameless\Debug\Tests;

use Nameless\Debug\ErrorHandler;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorHandler()
    {
        $error_handler = $this->getMock('ErrorHandler', array('handleError'));
        $this->assertInstanceOf('ErrorHandler', $error_handler);

        /*$exceptionHandler->expects($this->once())
            ->method('handleError')
            ->will($this->returnCallback($exceptionCheck))
        ;

        ErrorHandler::register();
        set_exception_handler(array($exceptionHandler, 'handle'));*/
    }
} 
<?php

/**
 * Nameless Debug package
 *
 * @copyright Copyright 2014, Corpsee.
 * @license   https://github.com/corpsee/nameless-debug/blob/master/LICENSE
 * @link      https://github.com/corpsee/nameles-debug
 */

namespace Nameless\Debug;

use Psr\Log\LoggerInterface;

/**
 * Class ExceptionHandler
 *
 * @package Nameless Debug
 * @author  Corpsee <poisoncorpsee@gmail.com>
 */
class ExceptionHandler
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return ExceptionHandler
     */
    public static function register(LoggerInterface $logger = null)
    {
        $handler = new static($logger);
        set_exception_handler([$handler, 'handleException']);

        return $handler;
    }

    /**
     * @param \Exception $exception
     */
    protected function renderException(\Exception $exception)
    {
        echo (string)$exception;
    }

    /**
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        try {
            $this->logException($exception);
            $this->renderException($exception);
        } catch (\Exception $e) {
            exit('twice exception!');
        }
    }

    /**
     * @param \Exception $exception
     */
    public function logException($exception) {
        if (null !== $this->logger) {
            $this->logger->error((string)$exception, (array)$exception);
        }
    }
}
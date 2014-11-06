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
    protected function logException($exception) {
        if (null !== $this->logger) {
            $this->logger->error((string)$exception, (array)$exception);
        }
    }

    /**
     * @param \Exception $exception
     * @param \Exception $previous_exception
     */
    protected function renderException(\Exception $exception, \Exception $previous_exception = null)
    {
        $output = (string)$exception . PHP_EOL;
        if (null !== $previous_exception) {
            $output .= 'Previous exception:' . PHP_EOL;
            $output .= (string)$previous_exception . PHP_EOL;
        }

        if (PHP_SAPI === 'cli') {
            echo $output;
        } else {
            echo '<pre>' . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }

    /**
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        restore_error_handler();
        restore_exception_handler();

        try {
            $this->logException($exception);
            $this->renderException($exception);
        } catch (\Exception $e) {
            $this->renderException($e, $exception);
            exit(1);
        }
    }
}
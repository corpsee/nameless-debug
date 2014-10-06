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
 * Class ErrorHandler
 *
 * @package Nameless Debug
 * @author  Corpsee <poisoncorpsee@gmail.com>
 */
class ErrorHandler
{
    /**
     * @var array
     */
    protected $levels = [
        E_WARNING           => 'E_WARNING',
        E_NOTICE            => 'E_NOTICE',
        E_USER_ERROR        => 'E_USER_ERROR',
        E_USER_WARNING      => 'E_USER_WARNING',
        E_USER_NOTICE       => 'E_USER_NOTICE',
        E_STRICT            => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED        => 'E_DEPRECATED',
        E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
        E_ERROR             => 'E_ERROR',
        E_CORE_ERROR        => 'E_CORE_ERROR',
        E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
        E_PARSE             => 'E_PARSE',
    ];

    /**
     * @var string
     */
    protected $reserved_memory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger          = $logger;
        $this->reserved_memory = str_repeat('x', 10240);
    }

    /**
     * @return ErrorHandler
     */
    public static function register()
    {
        $handler = new static();

        ini_set('display_errors', 0);
        set_error_handler([$handler, 'handleError']);
        register_shutdown_function([$handler, 'handleFatalError']);

        return $handler;
    }

    /**
     * @param \Exception $exception
     */
    protected function handleException(\Exception $exception)
    {
        $exception_handler = new ExceptionHandler($this->logger);
        $exception_handler->handleException($exception);
    }

    /**
     * @param integer $level
     * @param string  $message
     * @param string  $file
     * @param integer $line
     *
     * @return boolean
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file, $line)
    {
        if (error_reporting() & $level) {
            $exception_level = isset($this->levels[$level]) ? $this->levels[$level] : $level;
            $exception       = new \ErrorException("{$exception_level}: {$message} in {$file} line {$line}", $level, $level, $file, $line);

            $trace = debug_backtrace(0);
            array_shift($trace);
            foreach ($trace as $trace_item) {
                if ($trace_item['function'] == '__toString') {
                    $this->handleException($exception);
                }
            }

            throw $exception;
        }
        return false;
    }

    /**
     * @throws \ErrorException
     */
    public function handleFatalError()
    {
        $this->reserved_memory = '';
        $error                 = error_get_last();

        if (isset($error['type']) && in_array((integer)$error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING], true)) {
            $exception = new \ErrorException($error['message'], $error['type'], $error['type'], $error['file'], $error['line']);
            $this->handleException($exception);
        }
    }
}
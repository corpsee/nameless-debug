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
        E_ERROR             => 'Fatal',
        E_WARNING           => 'Warning',
        E_NOTICE            => 'Notice',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
        E_DEPRECATED        => 'Deprecated',
        E_USER_DEPRECATED   => 'User Deprecated',
        E_ERROR             => 'Error',
        E_CORE_ERROR        => 'Core Error',
        E_COMPILE_ERROR     => 'Compile Error',
        E_PARSE             => 'Parse',
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

        set_error_handler([$handler, 'handleError']);

        ob_start();
        register_shutdown_function([$handler, 'handleFatalError']);

        return $handler;
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
        if (0 === error_reporting()) {
            return false;
        } elseif (error_reporting() & $level) {
            $exception_level = isset($this->levels[$level]) ? $this->levels[$level] : $level;
            $exception       = new \ErrorException("{$exception_level}: {$message} in {$file} line {$line}", $level, $level, $file, $line);

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
            while (ob_get_level()) {
                ob_end_clean();
            }
            $exception = new \ErrorException($error['message'], $error['type'], $error['type'], $error['file'], $error['line']);

            $exception_handler = new ExceptionHandler($this->logger);
            $exception_handler->handleException($exception);
        } else {
            ob_end_flush();
        }
        exit(1);
    }
}
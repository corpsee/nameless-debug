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
     *
     * @return ErrorHandler
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger          = $logger;
        $this->reserved_memory = str_repeat('x', 10240);

        return $this;
    }

    /**
     * @return ErrorHandler
     */
    public function register()
    {
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleFatalError']);

        return $this;
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
     * @param \Exception $exception
     */
    protected function logException($exception)
    {
        if (null !== $this->logger) {
            $this->logger->error((string)$exception, (array)$exception);
        }
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
            $exception       = new \ErrorException("{$exception_level}: {$message}", $level, $level, $file, $line);

            $this->logException($exception);

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

        $error  = error_get_last();
        $errors = [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING];

        if (isset($error['type']) && in_array((integer)$error['type'], $errors, true)) {
            $exception = new \ErrorException(
                "{$this->levels[$error['type']]}: {$error['message']}",
                $error['type'],
                $error['type'],
                $error['file'],
                $error['line']
            );

            $this->logException($exception);
            $this->handleException($exception);
        }
    }
}

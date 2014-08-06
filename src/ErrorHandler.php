<?php

/**
 * Nameless Debug package
 *
 * @copyright    Copyright 2014, Corpsee.
 * @license      https://github.com/corpsee/nameless-debug/blob/master/LICENSE
 * @link         https://github.com/corpsee/nameles-debug
 */

namespace Nameless\Debug;

/**
 * Class ErrorHandler
 *
 * @package Nameless Debug
 * @author  Corpsee <poisoncorpsee@gmail.com>
 */
class ErrorHandler
{
    //TODO: logging exception/shutdown
    /**
     * @var array
     */
    protected $levels = [
        E_WARNING           => 'Warning',
        E_NOTICE            => 'Notice',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
        E_DEPRECATED        => 'Deprecated',
        E_USER_DEPRECATED   => 'User Deprecated',
    ];

    /**
     * @param integer $level
     * @param string  $message
     * @param string  $file
     * @param integer $line
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file, $line)
    {
        if (error_reporting() === 0) {
            return;
        }

        $exception_level = isset($this->levels[$level]) ? $this->levels[$level] : $level;
        throw new \ErrorException(sprintf(
            '%s: %s in %s line %d',
            $exception_level,
            $message,
            $file,
            $line
        ), 0, $level, $file, $line);
    }

    public static function register()
    {
        $handler = new static();
        set_error_handler(array($handler, 'handleError'));
    }
}
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
        E_ERROR             => 'Error',
        E_CORE_ERROR        => 'Core Error',
        E_COMPILE_ERROR     => 'Compile Error',
        E_PARSE             => 'Parse',
    ];

    /**
     * @var integer
     */
    protected $level;

    /**
     * @var boolean
     */
    protected $displayErrors;

    /**
     * @param integer $level
     * @param boolean $displayErrors
     */
    public function __construct($level = null, $displayErrors = null)
    {
        $this->setLevel($level);
        $this->setDisplayErrors($displayErrors);
    }

    /**
     * @param integer $level
     * @param boolean $displayErrors
     */
    public static function register($level = null, $displayErrors = null)
    {
        $handler = new static($level, $displayErrors);
        set_error_handler([$handler, 'handleError']);
        register_shutdown_function([$handler, 'handleFatalError']);
    }

    /**
     * @param integer $level
     */
    protected function setLevel($level = null)
    {
        $this->level = (null === $level) ? error_reporting() : (integer)$level;
    }

    /**
     * @param boolean $displayErrors
     */
    protected function setDisplayErrors($displayErrors = null)
    {
        $this->level = (null === $displayErrors) ? ini_get('display_errors') : (boolean)$displayErrors;
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
        if (0 === $this->level) {
            return false;
        } elseif ($this->level & $level) {
            $exception_level = isset($this->levels[$level]) ? $this->levels[$level] : $level;
            throw new \ErrorException("{$exception_level}: {$message} in {$file} line {$line}", 0, $level, $file, $line);
        }
    }
}
<?php

/**
 * Nameless Debug package
 *
 * @copyright Copyright 2014, Corpsee.
 * @license   https://github.com/corpsee/nameless-debug/blob/master/LICENSE
 * @link      https://github.com/corpsee/nameles-debug
 */

namespace Nameless\Debug;

/**
 * Class ExceptionHandler
 *
 * @package Nameless Debug
 * @author  Corpsee <poisoncorpsee@gmail.com>
 */
class ExceptionHandler
{
    /**
     * @return ExceptionHandler
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * @return ExceptionHandler
     */
    public function register()
    {
        set_exception_handler([$this, 'handleException']);

        return $this;
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
        try {
            $this->renderException($exception);
        } catch (\Exception $e) {
            $this->renderException($e, $exception);
        }
        exit(1);
    }
}
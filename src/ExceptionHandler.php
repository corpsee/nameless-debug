<?php

/**
 * Nameless debug package
 *
 * @package Nameless debug
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Debug;

/**
 * Class ExceptionHandler
 *
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
        if (PHP_SAPI === 'cli') {
            $output = (string)$exception . PHP_EOL;
            if (null !== $previous_exception) {
                $output .= PHP_EOL . (string)$previous_exception . PHP_EOL;
            }
            echo $output;
        } else {
            $pretty_output = "
<html>
    <head>
        <title>Uncaught Exceptions</title>
        <style>body{font-family:sans-serif;color:#333;margin:2em;}code{background:#ccc;padding:2px 6px}</style>
    </head>
<body>
    <h1>Uncaught Exceptions</h1>";
            $pretty_output .= $this->prettyException($exception);
            if (null !== $previous_exception) {
                $pretty_output .= $this->prettyException($previous_exception);
            }
            echo $pretty_output . "</body></html>";
        }
    }

    /**
     * @param \Exception $exception
     *
     * @return string
     */
    protected function prettyException(\Exception $exception)
    {
        $message = htmlspecialchars($exception->getMessage(), ENT_QUOTES);
        $file    = htmlspecialchars($exception->getFile(), ENT_QUOTES);
        $line    = (integer)$exception->getLine();
        $stack   = htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES);

        return "
<h2>{$message}</h2>
<p>
    <code>In file: '{$file}' on line #{$line}</code>
</p>
<h3>Stack trace:</h3>
<p>
    <pre>{$stack}</pre>
</p>";
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
    }
}

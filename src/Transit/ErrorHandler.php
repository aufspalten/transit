<?php

namespace Transit;

class ErrorHandler
{
    /**
     * Taken from Symphony
     * https://github.com/symfony/error-handler/blob/a6c529f2970a/ErrorHandler.php#L158-L182
     * 
     * Calls a function and turns any PHP error into \ErrorException.
     *
     * @return mixed What $function(...$arguments) returns
     *
     * @throws \ErrorException When $function(...$arguments) triggers a PHP error
     */
    public static function call(callable $function, ...$arguments)
    {
        set_error_handler(static function (int $type, string $message, string $file, int $line) {
            if (__FILE__ === $file) {
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
                $file = $trace[2]['file'] ?? $file;
                $line = $trace[2]['line'] ?? $line;
            }

            throw new \ErrorException($message, 0, $type, $file, $line);
        });

        try {
            return $function(...$arguments);
        } finally {
            restore_error_handler();
        }
    }
}

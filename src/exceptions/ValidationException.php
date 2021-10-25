<?php

namespace grintea\exceptions;

class ValidationException extends \Exception {

    public function __construct(array $validators, $code = 0, Throwable $previous = null)
    {
        $message = implode(\PHP_EOL,$validators);
        parent::__construct($message, $code, $previous);
    }

}
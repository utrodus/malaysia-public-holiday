<?php

namespace MalaysiaHoliday\Exceptions;

class RegionException extends \Exception
{
    /**
     * RegionException constructor.
     *
     * @param string $message The error message.
     * @param int $code The error code.
     * @param \Throwable|null $previous The previous exception used for chaining.
     */
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
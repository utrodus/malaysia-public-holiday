<?php

namespace Tests\MalaysiaHoliday\Exceptions;

use MalaysiaHoliday\Exceptions\RegionException;
use PHPUnit\Framework\TestCase;

class RegionExceptionTest extends TestCase
{
    public function testExceptionInstance(): void
    {
        $exception = new RegionException('Invalid region provided.');
        $this->assertInstanceOf(RegionException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testExceptionMessage(): void
    {
        $message = 'Test region exception message.';
        $exception = new RegionException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionCode(): void
    {
        $code = 123;
        $exception = new RegionException('Error', $code);
        $this->assertSame($code, $exception->getCode());
    }

    public function testPreviousException(): void
    {
        $previous = new \Exception('Previous error.');
        $exception = new RegionException('Chained exception.', 0, $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
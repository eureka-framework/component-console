<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Input;

use Eureka\Component\Console\Exception\InvalidInputException;
use Eureka\Component\Console\Input\Input;
use Eureka\Component\Console\Input\StreamInput;
use PHPUnit\Framework\TestCase;

class StreamInputTest extends TestCase
{
    /**
     * @return resource
     */
    private function getStream()
    {
        $stream = fopen('php://memory', 'r+');
        if (!is_resource($stream)) {
            $this->markTestSkipped('Cannot test method because cannot open memory stream resource');
        }

        return $stream;
    }

    public function testICanReadLineFromStream(): void
    {
        //~ Given
        $stream = $this->getStream();

        //~ Write & reset stream offset
        fputs($stream, 'any string as input');
        fseek($stream, 0);

        //~ When
        $input = new StreamInput($stream);

        //~ Then
        $this->assertInstanceOf(Input::class, $input);
        $this->assertSame('any string as input', $input->readLine());
    }

    public function testICanReadStringFromStream(): void
    {
        //~ Given
        $stream = $this->getStream();

        //~ Write & reset stream offset
        fputs($stream, 'any string as input');
        fseek($stream, 0);

        //~ When
        $input = new StreamInput($stream);

        //~ Then
        $this->assertInstanceOf(Input::class, $input);
        $this->assertSame('any', $input->readString());
    }

    public function testICanReadIntFromStream(): void
    {
        //~ Given
        $stream = $this->getStream();

        //~ Write & reset stream offset
        fputs($stream, '12345');
        fseek($stream, 0);

        //~ When
        $input = new StreamInput($stream);

        //~ Then
        $this->assertInstanceOf(Input::class, $input);
        $this->assertSame(12345, $input->readInt());
    }

    public function testICanReadFloatFromStream(): void
    {
        //~ Given
        $stream = $this->getStream();

        //~ Write & reset stream offset
        fputs($stream, '123.45');
        fseek($stream, 0);

        //~ When
        $input = new StreamInput($stream);

        //~ Then
        $this->assertInstanceOf(Input::class, $input);
        $this->assertSame(123.45, $input->readFloat());
    }

    public function testICanReadBoolFromStream(): void
    {
        //~ Given
        $stream = $this->getStream();

        //~ Write & reset stream offset
        fputs($stream, '1');
        fseek($stream, 0);

        //~ When
        $input = new StreamInput($stream);

        //~ Then
        $this->assertInstanceOf(Input::class, $input);
        $this->assertSame(true, $input->readBool());
    }

    public function testICanReadFormatFromStream(): void
    {
        //~ Given
        $stream = $this->getStream();

        //~ Write & reset stream offset
        fputs($stream, 'any 12 34.56 0');
        fseek($stream, 0);

        //~ When
        $input = new StreamInput($stream);

        //~ Then
        $this->assertInstanceOf(Input::class, $input);
        $this->assertSame(['any', 12, 34.56, 0], $input->readFormat('%s %d %f %d'));
    }

    public function testAnExceptionIsThrownWhenAnInvalidStreamIsGiven(): void
    {
        //~ Given
        /** @var resource $stream */
        $stream = 0;

        //~ Then
        $this->expectException(InvalidInputException::class);

        //~ When
        new StreamInput($stream);
    }
}

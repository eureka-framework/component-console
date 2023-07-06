<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Output;

use Eureka\Component\Console\Exception\InvalidOutputException;
use Eureka\Component\Console\Output\Output;
use Eureka\Component\Console\Output\StreamOutput;
use PHPUnit\Framework\TestCase;

class StreamOutputTest extends TestCase
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

    public function testStreamOutput(): void
    {
        //~ When
        $stream = $this->getStream();
        $output = new StreamOutput($stream, false);
        $output->write('any');
        $output->writeln(' string');

        //~ Then
        $this->assertInstanceOf(Output::class, $output);

        fseek($stream, 0);

        $string = fgets($stream);
        $this->assertSame("any string" . PHP_EOL, $string);
    }

    public function testStreamOutputWithQuietOption(): void
    {
        //~ When
        $stream = $this->getStream();
        $output = new StreamOutput($stream, true);
        $output->write('any');
        $output->writeln(' string');

        //~ Then
        $this->assertInstanceOf(Output::class, $output);

        fseek($stream, 0);

        $string = fgets($stream); // No output written, so should be false
        $this->assertSame(false, $string);
    }

    public function testAnExceptionIsThrownWhenAnInvalidStreamIsGiven(): void
    {
        //~ Given
        /** @var resource $stream */
        $stream = 0;

        //~ Then
        $this->expectException(InvalidOutputException::class);

        //~ When
        new StreamOutput($stream, true);
    }
}

<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Output;

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
        fseek($stream, 0);

        $string = fgets($stream); // No output written, so should be false
        $this->assertFalse($string);
    }

    public function testAnExceptionIsThrownWhenAnInvalidStreamIsGiven(): void
    {
        //~ Given
        $stream = fopen(__FILE__, 'r');
        if (!is_resource($stream)) {
            $this->markTestSkipped('Cannot test method because cannot open file stream resource');
        }
        fclose($stream); // Close the stream to simulate an invalid resource

        //~ Then
        $this->expectException(InvalidOutputException::class);

        //~ When
        new StreamOutput($stream, true);
    }
}

<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Terminal;

use Eureka\Component\Console\Output\StreamOutput;
use Eureka\Component\Console\Terminal\Cursor;
use Eureka\Component\Console\Terminal\Terminal;
use http\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CursorTest extends TestCase
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

    /**
     * @param string $method
     * @param string $expect
     * @return void
     *
     * @dataProvider methodAndExpectProvider
     */
    public function testMethod(string $method, string $expect): void
    {
        //~ Given
        $stream = $this->getStream();
        $csi    = Terminal::CSI;

        //~ When
        $cursor = new Cursor(new StreamOutput($stream, false));
        $cursor->$method();

        //~ Then
        fseek($stream, 0);
        $string = fgets($stream);
        $this->assertSame($expect, $string);
    }

    public function testAnExceptionIsThrownWhenGivenStreamInputIsNotAResource(): void
    {
        //~ Given
        $stream = $this->getStream();
        /** @var resource $inputStream */
        $inputStream = 'any';

        //~ Then
        $this->expectException(\InvalidArgumentException::class);

        //~ When
        new Cursor(new StreamOutput($stream, false), inputStream: $inputStream);
    }

    /**
     * @return array<string, string[]>
     */
    public static function methodAndExpectProvider(): array
    {
        $csi    = Terminal::CSI;
        return [
            'up'         => ['up', "{$csi}1A"],
            'down'       => ['down', "{$csi}1B"],
            'right'      => ['right', "{$csi}1C"],
            'left'       => ['left', "{$csi}1D"],
            'lineDown'   => ['lineDown', "{$csi}1E"],
            'lineUp'     => ['lineUp', "{$csi}1F"],
            'column'     => ['column', "{$csi}1G"],
            'to'         => ['to', "{$csi}1;1H"],
            'clear'      => ['clear', "{$csi}2J"],
            'clearLine'  => ['clearLine', "{$csi}2K"],
            'scrollUp'   => ['scrollUp', "{$csi}1S"],
            'scrollDown' => ['scrollDown', "{$csi}1T"],
            'save'       => ['save', "{$csi}s"],
            'restore'    => ['restore', "{$csi}u"],
            'show'       => ['show', "{$csi}?25h"],
            'hide'       => ['hide', "{$csi}?25l"],
        ];
    }
}

<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Terminal;

use Eureka\Component\Console\Output\StreamOutput;
use Eureka\Component\Console\Terminal\Cursor;
use Eureka\Component\Console\Terminal\Terminal;
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

    public function testUp(): void
    {
        //~ Given
        $stream = $this->getStream();
        $csi    = Terminal::CSI;

        //~ When
        $cursor = new Cursor(new StreamOutput($stream, false));
        $cursor->up();

        //~ Then
        fseek($stream, 0);
        $string = fgets($stream);
        $this->assertSame("{$csi}1A", $string);
    }
}

<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Input;

use Eureka\Component\Console\Input\Input;
use Eureka\Component\Console\Input\NullInput;
use PHPUnit\Framework\TestCase;

class NullInputTest extends TestCase
{
    public function testNullInput(): void
    {
        //~ When
        $input = new NullInput();

        //~ Then
        $this->assertNull($input->readLine());
        $this->assertSame([], $input->readFormat(''));
        $this->assertSame('', $input->readString());
        $this->assertSame(0, $input->readInt());
        $this->assertSame(0.0, $input->readFloat());
        $this->assertFalse($input->readBool());
    }
}

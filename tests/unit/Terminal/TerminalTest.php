<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Terminal;

use Eureka\Component\Console\Output\Output;
use Eureka\Component\Console\Output\StreamOutput;
use Eureka\Component\Console\Terminal\Cursor;
use Eureka\Component\Console\Terminal\Shell;
use Eureka\Component\Console\Terminal\Terminal;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TerminalTest extends TestCase
{
    /**
     * @return resource
     */
    private function getStream()
    {
        $stream = fopen('php://memory', 'r+');
        if (!is_resource($stream)) {
            throw new \UnexpectedValueException('Unable to open memory stream');
        }

        return $stream;
    }

    private function getFormatStty1(): string
    {
        return <<<SHELL
        speed 38400 baud; rows 20; columns 40; line = 0;
        intr = ^C; quit = ^\; erase = ^?; kill = ^U; eof = ^D; eol = <undef>; eol2 = <undef>; swtch = <undef>; start = ^Q; stop = ^S; susp = ^Z; rprnt = ^R; werase = ^W; lnext = ^V;
        discard = ^O; min = 1; time = 0;
        -parenb -parodd -cmspar cs8 -hupcl -cstopb cread -clocal -crtscts
        -ignbrk -brkint -ignpar -parmrk -inpck -istrip -inlcr -igncr icrnl ixon -ixoff -iuclc -ixany -imaxbel -iutf8
        opost -olcuc -ocrnl onlcr -onocr -onlret -ofill -ofdel nl0 cr0 tab0 bs0 vt0 ff0
        isig icanon iexten echo echoe echok -echonl -noflsh -xcase -tostop -echoprt echoctl echoke -flusho -extproc
        SHELL;
    }

    private function getFormatStty2(): string
    {
        return <<<SHELL
        speed 38400 baud; 30 rows; 60 columns; line = 0;
        intr = ^C; quit = ^\; erase = ^?; kill = ^U; eof = ^D; eol = <undef>; eol2 = <undef>; swtch = <undef>; start = ^Q; stop = ^S; susp = ^Z; rprnt = ^R; werase = ^W; lnext = ^V;
        discard = ^O; min = 1; time = 0;
        -parenb -parodd -cmspar cs8 -hupcl -cstopb cread -clocal -crtscts
        -ignbrk -brkint -ignpar -parmrk -inpck -istrip -inlcr -igncr icrnl ixon -ixoff -iuclc -ixany -imaxbel -iutf8
        opost -olcuc -ocrnl onlcr -onocr -onlret -ofill -ofdel nl0 cr0 tab0 bs0 vt0 ff0
        isig icanon iexten echo echoe echok -echonl -noflsh -xcase -tostop -echoprt echoctl echoke -flusho -extproc
        SHELL;
    }

    /**
     * @return Shell&MockObject
     * @throws Exception
     */
    private function getShell(string $format): Shell
    {
        $shell = $this->createMock(Shell::class);
        $shell->method('exec')->willReturn($format);

        return $shell;
    }

    /**
     * @throws Exception
     */
    public function testTerminal(): void
    {
        //~ Given
        $stream = $this->getStream();
        $shell  = $this->getShell($this->getFormatStty1());

        //~ When
        $terminal = new Terminal(new StreamOutput($stream, false), $shell);
        $terminal->clear();
        $csi = Terminal::CSI;
        fseek($stream, 0);
        $string = fgets($stream);

        //~ Then
        $this->assertSame("{$csi}2J", $string);
    }

    /**
     * @throws Exception
     */
    public function testInitFromEnvVars(): void
    {
        //~ Given
        $stream = $this->getStream();
        $shell  = $this->getShell($this->getFormatStty1());

        putenv("COLUMNS=20");
        putenv("LINES=10");

        //~ When
        $terminal = new Terminal(new StreamOutput($stream, false), $shell);

        //~ Then
        $this->assertSame(20, $terminal->getWidth());
        $this->assertSame(10, $terminal->getHeight());

        putenv("COLUMNS");
        putenv("LINES");
    }

    /**
     * @throws Exception
     */
    public function testInitFromFormat1(): void
    {
        //~ Given
        $stream = $this->getStream();
        $shell  = $this->getShell($this->getFormatStty1());

        //~ When
        $terminal = new Terminal(new StreamOutput($stream, false), $shell);

        //~ Then
        $this->assertSame(40, $terminal->getWidth());
        $this->assertSame(20, $terminal->getHeight());
    }

    /**
     * @throws Exception
     */
    public function testInitFromFormat2(): void
    {
        //~ Given
        $stream = $this->getStream();
        $shell  = $this->getShell($this->getFormatStty2());

        //~ When
        $terminal = new Terminal(new StreamOutput($stream, false), $shell);

        //~ Then
        $this->assertSame(60, $terminal->getWidth());
        $this->assertSame(30, $terminal->getHeight());
    }
}

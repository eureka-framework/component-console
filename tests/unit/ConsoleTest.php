<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit;

use Eureka\Component\Console\Console;
use Eureka\Component\Console\Input\StreamInput;
use Eureka\Component\Console\Output\StreamOutput;
use Lcobucci\Clock\FrozenClock;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ConsoleTest extends TestCase
{
    /**
     * @param resource $streamIn
     * @param resource $streamOut
     * @param resource $streamOutErr
     * @param list<string> $argv
     * @return Console
     */
    private function getConsole($streamIn, $streamOut, $streamOutErr, array $argv = []): Console
    {
        $clock = new FrozenClock(new \DateTimeImmutable('2015-10-15 01:22:00'));

        $input     = new StreamInput($streamIn);
        $output    = new StreamOutput($streamOut, false);
        $outputErr = new StreamOutput($streamOutErr, false);

        return new Console($clock, $argv, $input, $output, $outputErr);
    }

    public function testConsoleWithoutScriptAsArgumentAndWithoutHelpOption(): void
    {
        /** @var resource $stream */
        $stream = fopen('php://memory', 'r+');

        $console = $this->getConsole($stream, $stream, $stream, ['bin/console']);
        $console->setLogger(new NullLogger());

        $console->setBaseNamespaces(['Eureka\Component\Console\Tests\Unit']);

        $console->before();
        $console->run();
        $console->after();

        //~ Read output stream
        fseek($stream, 0);
        $content = fread($stream, 8192);
        $expected = <<<OUTPUT
         *** RUN - HELP ***
        
        
        [92m[1mUse    : [0m[97m[1mbin/console ... [OPTION]...[0m
        [32m[1mOPTIONS:[0m
        [1m  -h,     --help                        [0mDisplay Help
        [1m          --no-color                    [0mDisable colors / styling (Can also be disabled with NO_COLOR env var)
        [1m          --debug                       [0mActivate debug mode (trace on exception if script is terminated with an exception)
        [1m          --time-limit=ARG              [0mSpecified time limit in seconds (default: 0 - unlimited)
        [1m          --memory-limit=ARG            [0mSpecified memory limit (128M, 1024M, 4G... - default: 256M)
        [1m          --error-reporting=ARG         [0mSpecified value for error-reporting (default: -1 - all)
        [1m          --error-display=ARG           [0mSpecified value for display_errors setting. Values: 0|1 Default: 1 (display)
        [1m          --quiet                       [0mForce disabled console output (if message are written on stream output)
        [1m          --with-header                 [0mEnable console lib message header
        [1m          --with-footer                 [0mEnable console lib messages footer
        [1m          --script=ARG                  [0mConsole class script to run (Example: database/console)[31m - MANDATORY[0m
        

        [31m ~~ EXCEPTION[2000]: Console Error: A script name must be provided![0m
        
        OUTPUT;

        //~ Then
        $this->assertSame($expected, $content);
    }


    public function testConsoleWithoutScriptAsArgumentButWithHelpOption(): void
    {
        /** @var resource $stream */
        $stream = fopen('php://memory', 'r+');

        $console = $this->getConsole($stream, $stream, $stream, ['bin/console', '-h']);
        $console->setLogger(new NullLogger());

        $console->setBaseNamespaces(['Eureka\Component\Console\Tests\Unit']);

        $console->before();
        $console->run();
        $console->after();

        //~ Read output stream
        fseek($stream, 0);
        $content = fread($stream, 8192);
        $expected = <<<OUTPUT
         *** RUN - HELP ***
        
        
        [92m[1mUse    : [0m[97m[1mbin/console ... [OPTION]...[0m
        [32m[1mOPTIONS:[0m
        [1m  -h,     --help                        [0mDisplay Help
        [1m          --no-color                    [0mDisable colors / styling (Can also be disabled with NO_COLOR env var)
        [1m          --debug                       [0mActivate debug mode (trace on exception if script is terminated with an exception)
        [1m          --time-limit=ARG              [0mSpecified time limit in seconds (default: 0 - unlimited)
        [1m          --memory-limit=ARG            [0mSpecified memory limit (128M, 1024M, 4G... - default: 256M)
        [1m          --error-reporting=ARG         [0mSpecified value for error-reporting (default: -1 - all)
        [1m          --error-display=ARG           [0mSpecified value for display_errors setting. Values: 0|1 Default: 1 (display)
        [1m          --quiet                       [0mForce disabled console output (if message are written on stream output)
        [1m          --with-header                 [0mEnable console lib message header
        [1m          --with-footer                 [0mEnable console lib messages footer
        [1m          --script=ARG                  [0mConsole class script to run (Example: database/console)[31m - MANDATORY[0m
        
        
        OUTPUT;

        //~ Then
        $this->assertSame($expected, $content);
    }

    public function testConsoleWithScript(): void
    {
        /** @var resource $stream */
        $stream = fopen('php://memory', 'r+');

        $console = $this->getConsole($stream, $stream, $stream, ['bin/console', 'script']);
        $console->setLogger(new NullLogger());

        $console->setBaseNamespaces(['Eureka\Component\Console\Tests\Unit']);

        $console->before();
        $console->run();
        $console->after();

        //~ Read output stream
        fseek($stream, 0);
        $content = fread($stream, 8192);
        $expected = <<<OUTPUT
        Mock Script
        
        OUTPUT;

        //~ Then
        $this->assertSame($expected, $content);
    }

    public function testConsoleWithScriptWithHeaderAndFooter(): void
    {
        /** @var resource $stream */
        $stream = fopen('php://memory', 'r+');
        $argv   = ['bin/console', 'script', '--with-header', '--with-footer'];

        $console = $this->getConsole($stream, $stream, $stream, $argv);
        $console->setLogger(new NullLogger());

        $console->setBaseNamespaces(['Eureka\Component\Console\Tests\Unit']);

        $console->before();
        $console->run();
        $console->after();

        //~ Read output stream
        fseek($stream, 0);
        $content = fread($stream, 8192);
        $expected = <<<OUTPUT
        [32m *** RUN - Script - 2015-10-15 01:22:00 ***[0m
        Mock Script
        [32m *** END SCRIPT - Time taken: 0s - 2015-10-15 01:22:00 ***[0m

        OUTPUT;

        //~ Then
        $this->assertSame($expected, $content);
    }

    public function testConsoleWithScriptHelpWithHeaderAndFooter(): void
    {
        /** @var resource $stream */
        $stream = fopen('php://memory', 'r+');
        $argv   = ['bin/console', 'script', '--help', '--with-header', '--with-footer'];

        $console = $this->getConsole($stream, $stream, $stream, $argv);
        $console->setLogger(new NullLogger());

        $console->setBaseNamespaces(['Eureka\Component\Console\Tests\Unit']);

        $console->before();
        $console->run();
        $console->after();

        //~ Read output stream
        fseek($stream, 0);
        $content = fread($stream, 8192);
        $expected = <<<OUTPUT
        [32m *** RUN - Script - HELP - 2015-10-15 01:22:00 ***[0m
        
        [92m[1mUse    : [0m[97m[1mbin/console Eureka/Component/Console/Tests/Unit/Script [OPTION]...[0m
        [32m[1mOPTIONS:[0m
        
        [32m *** END SCRIPT - Time taken: 0s - 2015-10-15 01:22:00 ***[0m
        
        OUTPUT;

        //~ Then
        $this->assertSame($expected, $content);
    }

    public function testConsoleWithNotScript(): void
    {
        /** @var resource $stream */
        $stream = fopen('php://memory', 'r+');
        $argv   = ['bin/console', 'NotScript'];

        $console = $this->getConsole($stream, $stream, $stream, $argv);
        $console->setLogger(new NullLogger());

        $console->setBaseNamespaces(['Eureka\Component\Console\Tests\Unit']);

        $console->before();
        $console->run();
        $console->after();

        //~ Read output stream
        fseek($stream, 0);
        $content = fread($stream, 8192);
        $expected = <<<OUTPUT

        [31m ~~ EXCEPTION[2004]: Current script must implement ScriptInterface interface ![0m

        OUTPUT;

        //~ Then
        $this->assertSame($expected, $content);
    }

    public function testConsoleWithNotScriptQuiet(): void
    {
        /** @var resource $stream */
        $stream = fopen('php://memory', 'r+');
        $argv   = ['bin/console', 'NotScript', '--quiet'];

        $console = $this->getConsole($stream, $stream, $stream, $argv);
        $console->setLogger(new NullLogger());

        $console->setBaseNamespaces(['Eureka\Component\Console\Tests\Unit']);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Current script must implement ScriptInterface interface !');

        $console->before();
        $console->run();
        $console->after();
    }

    public function testConsoleWithClassNotFound(): void
    {
        /** @var resource $stream */
        $stream = fopen('php://memory', 'r+');
        $argv   = ['bin/console', 'AScript'];

        $console = $this->getConsole($stream, $stream, $stream, $argv);
        $console->setLogger(new NullLogger());

        $console->setBaseNamespaces(['Eureka\Component\Console\Tests\Unit']);

        $console->before();
        $console->run();
        $console->after();

        //~ Read output stream
        fseek($stream, 0);
        $content = fread($stream, 8192);
        $expected = <<<OUTPUT

        [31m ~~ EXCEPTION[2003]: Current script class does not exists (script: 'AScript') ![0m

        OUTPUT;

        //~ Then
        $this->assertSame($expected, $content);
    }
}

<?php

/*
 * Copyright (c) Eureka
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Console\Tests;

use Eureka\Component\Console\Argument\Argument;
use Eureka\Component\Console\Console;
use Eureka\Component\Console\IO\Out;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Class Test for Progress class.
 *
 * @author Romain Cottard
 * @group unit
 */
class ScriptTest extends TestCase
{
    const NB_ELEMENTS = 10;

    public function testConsoleWithoutArgumentMustDisplayHelpAndException()
    {
        //~ Mock parameters
        $mockArguments = ['bin/console'];
        Argument::getInstance()->parse($mockArguments);
        Out::allowBuffering(true);

        $assert = <<<ASSERT
 *** RUN - HELP ***


Use    : bin/console ... [OPTION]...
OPTIONS:
  -h,     --help                        Reserved - Display Help
          --color                       Activate colors (do not activate when redirect output in log file, colors are non-printable chars)
          --debug                       Activate debug mode (trace on exception if script is terminated with an exception)
          --time-limit=ARG              Specified time limit in seconds (default: 0 - unlimited)
          --memory-limit=ARG            Specified memory limit (128M, 1024M, 4G... - default: 256M)
          --error-reporting=ARG         Specified value for error-reporting (default: -1 - all)
          --error-display=ARG           Specified value for display_errors setting. Values: 0|1 Default: 1 (display)
          --quiet                       Force disabled console lib messages (header, footer, timer...)
          --name=ARG                    Console class script to run (Example: Database/Console) - MANDATORY


 ~~ EXCEPTION[2000]: Console Error: A script name must be provided!
ASSERT;

        ob_start();
        $console = new Console($mockArguments, null, new NullLogger());
        $console->setBaseNamespaces(['Application/Script']);
        $console->before();
        $console->run();
        $console->after();
        $buffer = ob_get_clean();

        $buffer = explode(PHP_EOL, rtrim($buffer));
        array_pop($buffer);
        $buffer = implode(PHP_EOL, $buffer);

        $this->assertEquals($assert, $buffer);
    }

    public function testConsoleWithArgumentHelpMustDisplayHelp()
    {
        //~ Mock parameters
        $mockArguments = ['bin/console', '--help'];
        Argument::getInstance()->parse($mockArguments);
        Out::allowBuffering(true);

        $assert = <<<ASSERT
 *** RUN - HELP ***


Use    : bin/console ... [OPTION]...
OPTIONS:
  -h,     --help                        Reserved - Display Help
          --color                       Activate colors (do not activate when redirect output in log file, colors are non-printable chars)
          --debug                       Activate debug mode (trace on exception if script is terminated with an exception)
          --time-limit=ARG              Specified time limit in seconds (default: 0 - unlimited)
          --memory-limit=ARG            Specified memory limit (128M, 1024M, 4G... - default: 256M)
          --error-reporting=ARG         Specified value for error-reporting (default: -1 - all)
          --error-display=ARG           Specified value for display_errors setting. Values: 0|1 Default: 1 (display)
          --quiet                       Force disabled console lib messages (header, footer, timer...)
          --name=ARG                    Console class script to run (Example: Database/Console) - MANDATORY

ASSERT;

        ob_start();
        $console = new Console($mockArguments);
        $console->before();
        $console->run();
        $console->after();
        $buffer = ob_get_clean();

        $buffer = explode(PHP_EOL, rtrim($buffer));
        array_pop($buffer);
        $buffer = implode(PHP_EOL, $buffer);

        $this->assertEquals($assert, $buffer);
    }

    public function testConsoleWithArgumentNameAndArgumentHelpMustRunScriptHelp()
    {
        //~ Mock parameters
        $mockArguments = ['bin/console', '--name=Console/Tests/MockScript', '--help'];
        Argument::getInstance()->parse($mockArguments);
        Out::allowBuffering(true);

        $assert = <<<ASSERT

Use    : bin/console Eureka\Component\Console\Tests\MockScript [OPTION]...
OPTIONS:
  -h,     --help                        Reserved - Display Help
  -i ARG, --id=ARG                      Argument ID

ASSERT;

        ob_start();
        $console = new Console($mockArguments);
        $console->before();
        $console->run();
        $console->after();
        $buffer = ob_get_clean();

        //~ Remove Header & Footer with date time values
        $buffer = explode(PHP_EOL, rtrim($buffer));
        array_shift($buffer);
        array_pop($buffer);
        $buffer = implode(PHP_EOL, $buffer);

        $this->assertEquals($assert, $buffer);
    }

    public function testConsoleWithArgumentNameMustRunScript()
    {
        //~ Mock parameters
        $mockArguments = ['bin/console', 'Console/Tests/MockScript'];
        Argument::getInstance()->parse($mockArguments);
        Out::allowBuffering(true);

        $assert = <<<ASSERT
Before.
Hello World!
After.
ASSERT;

        ob_start();
        $console = new Console($mockArguments);
        $console->before();
        $console->run();
        $console->after();
        $buffer = ob_get_clean();

        //~ Remove Header & Footer with date time values
        $buffer = explode(PHP_EOL, rtrim($buffer));
        $beforeText = array_shift($buffer);
        array_shift($buffer);
        array_pop($buffer);
        array_unshift($buffer, $beforeText);  // Re-add before text in array
        $buffer = implode(PHP_EOL, $buffer);

        $this->assertEquals($assert, $buffer);
    }

    public function testAnExceptionIsThrownWhenITryToExecuteScriptWithoutRequiredInterface()
    {
        //~ Mock parameters
        $mockArguments = ['bin/console', 'Console/Tests/MockNoScript', '--quiet'];
        Argument::getInstance()->parse($mockArguments);

        $this->expectExceptionCode(2004);
        $this->expectException(\LogicException::class);

        $console = new Console($mockArguments);
        $console->before();
        $console->run();
    }

    public function testAnExceptionIsThrownWhenITryToExecuteNonExecutableScript()
    {
        //~ Mock parameters
        $mockArguments = ['bin/console', 'Console/Tests/MockNotExecutableScript', '--quiet'];
        Argument::getInstance()->parse($mockArguments);

        $this->expectExceptionCode(2005);
        $this->expectException(\LogicException::class);

        $console = new Console($mockArguments);
        $console->before();
        $console->run();
    }

    public function testAnExceptionIsThrownWhenITryToExecuteNotExistantScript()
    {
        //~ Mock parameters
        $mockArguments = ['bin/console', 'Console/Tests/AnyScript', '--quiet'];
        Argument::getInstance()->parse($mockArguments);

        $this->expectExceptionCode(2003);
        $this->expectException(\RuntimeException::class);

        $console = new Console($mockArguments);
        $console->before();
        $console->run();
    }
}

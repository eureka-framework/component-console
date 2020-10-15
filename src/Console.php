<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console;

use Eureka\Component\Console\Exception\StopAfterHelpException;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class to execute specified console scripts.
 *
 * @author Romain Cottard
 */
class Console
{
    use LoggerAwareTrait;

    /** @var float $time Timer for script */
    protected float $time = 0.0;

    /** @var bool $isVerbose Set true to display header/footer script message (name, time...) */
    protected bool $isVerbose = true;

    /** @var Argument\Argument $argument Argument object */
    protected Argument\Argument $argument;

    /** @var ContainerInterface|null */
    protected ?ContainerInterface $container = null;

    /** @var int $exitCode Exit code script. */
    protected int $exitCode = 0;

    /** @var array $baseNamespaces Base namespaces for scripts class to execute. */
    protected array $baseNamespaces = ['Eureka\Component'];

    /**
     * Class constructor.
     *
     * @param array $args List of arguments for current script to execute.
     * @param ContainerInterface|null $container
     * @param LoggerInterface|null $logger
     */
    public function __construct(array $args, ContainerInterface $container = null, LoggerInterface $logger = null)
    {
        $this->argument  = Argument\Argument::getInstance()->parse($args);
        $this->container = $container;

        if ($logger !== null) {
            $this->setLogger($logger);
        }
    }

    /**
     * @return ContainerInterface|null
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }

    /**
     * Set base namespaces.
     *
     * @param  string[] $baseNamespaces
     * @return $this
     */
    public function setBaseNamespaces(array $baseNamespaces = []): self
    {
        foreach ($baseNamespaces as $baseNamespace) {
            $this->baseNamespaces[] = trim($baseNamespace, '\\');
        }

        return $this;
    }

    /**
     * Display console lib help
     *
     * @return void
     */
    protected function help(): void
    {
        $style = new Style\Style(' *** RUN - HELP ***');
        IO\Out::std($style->colorForeground(Style\Color::GREEN)->get());
        IO\Out::std('');

        $help = new Help('...');
        $help->addArgument('', 'color', 'Activate colors (do not activate when redirect output in log file, colors are non-printable chars)', false, false);
        $help->addArgument('', 'debug', 'Activate debug mode (trace on exception if script is terminated with an exception)', false, false);
        $help->addArgument('', 'time-limit', 'Specified time limit in seconds (default: 0 - unlimited)', true, false);
        $help->addArgument('', 'memory-limit', 'Specified memory limit (128M, 1024M, 4G... - default: 256M)', true, false);
        $help->addArgument('', 'error-reporting', 'Specified value for error-reporting (default: -1 - all)', true, false);
        $help->addArgument('', 'error-display', 'Specified value for display_errors setting. Values: 0|1 Default: 1 (display)', true, false);
        $help->addArgument('', 'quiet', 'Force disabled console lib messages (header, footer, timer...)', false, false);
        $help->addArgument('', 'name', 'Console class script to run (Example: Database/Console)', true, true);

        $help->display();
    }

    /**
     * This method is executed before main method of console.
     * - init timer
     * - init error_reporting
     * - init time limit for script
     * - init verbose mode
     *
     * @return void
     */
    public function before(): void
    {
        // ~ Init timer
        $this->time = -microtime(true);

        // ~ Reporting all error (default: all error) !
        error_reporting((int) $this->argument->get('error-reporting', null, - 1));
        ini_set('display_errors', (string) ((int) $this->argument->get('error-display', null, 1)));

        // ~ Set limit time to 0 (default: unlimited) !
        set_time_limit((int) $this->argument->get('time-limit', null, 0));

        // Set memory limit
        ini_set('memory_limit', (string) $this->argument->get('memory-limit', null, '256M'));

        $this->isVerbose = !$this->argument->has('quiet');
    }

    /**
     * This method is executed after main method of console.
     * - display footer script data (timer)
     *
     * @return void
     */
    public function after(): void
    {
        // ~ Display footer script timer
        $this->time += microtime(true);
        $style = new Style\Style(' *** END SCRIPT - Time taken: ' . round($this->time, 2) . 's - ' . date('Y-m-d H:i:s') . ' ***');
        $style->color('fg', Style\Color::GREEN);

        if (!$this->argument->has('script-no-header')) {
            IO\Out::std($style->get());
        }
    }

    /**
     * Terminate script with correct execution code.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function terminate(): void
    {
        exit($this->exitCode);
    }

    /**
     * This method is main method for console lib.
     * - display console lib help
     * - OR display script help (if script name is defined)
     * - OR execute script
     *
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
        $script           = null;
        $beforeHasBeenRun = false;

        try {
            $scriptName = $this->getScriptName();
            $script     = $this->getScriptInstance($scriptName);
            $script->setContainer($this->getContainer());

            $this->handleHelp($scriptName, $script);

            $beforeHasBeenRun = $this->handleRun($scriptName, $script);
        } catch (StopAfterHelpException $exception) {
            //~ Hard break, but continue to finally
        } catch (\Exception $exception) {
            $this->exitCode = 1;

            if (!$this->isVerbose) {
                throw $exception;
            }

            if ($this->logger instanceof LoggerInterface && !$exception instanceof Exception\AlreadyLoggedException) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception, 'type' => 'console.log']); // @codeCoverageIgnore
            }

            $style = new Style\Style(' ~~ EXCEPTION[' . $exception->getCode() . ']: ' . $exception->getMessage());
            $style->color('bg', Style\Color::RED);
            IO\Out::std(PHP_EOL . $style->get());

            if ($this->argument->has('debug')) {
                // @codeCoverageIgnoreStart
                echo $exception->getFile() . PHP_EOL;
                echo $exception->getLine() . PHP_EOL;
                echo $exception->getTraceAsString() . PHP_EOL;
                // @codeCoverageIgnoreEnd
            }
        } finally {
            if ($beforeHasBeenRun && $script instanceof ScriptInterface) {
                // ~ Execute this method after execution of main script method.
                $script->after();
            }
        }
    }

    /**
     * @param string $scriptName
     * @param ScriptInterface $script
     * @return void
     */
    private function handleHelp(string $scriptName, ScriptInterface $script): void
    {
        if (!$this->argument->has('help')) {
            return;
        }

        $style = new Style\Style();
        $style->setText(' *** RUN - ' . $scriptName . ' - HELP - ' . date('Y-m-d H:i:s') . ' ***');
        $style->color('fg', Style\Color::GREEN);

        IO\Out::std($style->get());
        $script->help();

        throw new StopAfterHelpException('help, stop!', 2001);
    }

    /**
     * @param string $scriptName
     * @param ScriptInterface $script
     * @return bool
     */
    private function handleRun(string $scriptName, ScriptInterface $script): bool
    {
        // ~ Execute this method before starting main script method
        $script->before();

        // ~ Display header script only after execution of before method (prevent error with start_session() for example).
        if (!$this->argument->has('script-no-header')) {
            $style = new Style\Style();
            $style->setText(' *** RUN - ' . $scriptName . ' - ' . date('Y-m-d H:i:s') . ' ***');
            $style->color('fg', Style\Color::GREEN);
            IO\Out::std($style->get());
        }

        // ~ Execute main script method.
        $script->run();

        return true;
    }

    /**
     * @return string
     */
    private function getScriptName(): string
    {
        $name = $this->argument->get('name', null, '');

        //~ Try to get default argument value if exist, to use it as a name.
        if (empty($name)) {
            $name = $this->argument->get('__default__', null, '');
        }

        $scriptName = str_replace('/', '\\', ucwords($name, '/\\'));

        // ~ Hook for console help
        if (empty($scriptName)) {
            $this->help();

            // ~ If no help asked, throw exception !
            if (!$this->argument->has('help')) {
                throw new \RuntimeException('Console Error: A script name must be provided!', 2000);
            }

            throw new StopAfterHelpException('help, stop!', 2001);
        }

        return $scriptName;
    }

    /**
     * @param string $scriptName
     * @return string
     */
    private function getClassName(string $scriptName): string
    {
        $classFound = false;
        $className  = '';
        foreach ($this->baseNamespaces as $baseNamespace) {
            $className = '\\' . trim($baseNamespace . '\\' . $scriptName, '\\');

            if (class_exists($className)) {
                $classFound = true;
                break;
            }
        }

        if (!$classFound) {
            throw new \RuntimeException('Current script class does not exists (script: "' . $scriptName . '") !', 2003);
        }

        return $className;
    }

    /**
     * Get a valid script Instance
     *
     * @param string $scriptName
     * @return ScriptInterface
     * @throws \LogicException
     */
    private function getScriptInstance(string $scriptName): ScriptInterface
    {
        $className = $this->getClassName($scriptName);

        try {
            if (empty($this->getContainer())) {
                throw new \RuntimeException();
            }

            $script = $this->getContainer()->get(ltrim(strtr($className, '/', '\\'), '\\')); // @codeCoverageIgnore
        } catch (\Exception $exception) {
            $script = new $className();
        }

        if (!($script instanceof ScriptInterface)) {
            throw new \LogicException('Current script must implement ScriptInterface interface !', 2004);
        }

        if (!$script->executable()) {
            throw new \LogicException('Console Error: Script is not executable !', 2005); // @codeCoverageIgnore
        }

        return $script;
    }
}

<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console;

use Eureka\Component\Console\Color\Bit4StandardColor;
use Eureka\Component\Console\Exception\StopAfterHelpException;
use Eureka\Component\Console\Input\Input;
use Eureka\Component\Console\Input\StreamInput;
use Eureka\Component\Console\Option\Option;
use Eureka\Component\Console\Option\OptionsParser;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Output\Output;
use Eureka\Component\Console\Output\StreamOutput;
use Eureka\Component\Console\Terminal\Terminal;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class to execute specified console scripts.
 *
 * @author Romain Cottard
 */
class Console implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected float $time = 0.0;

    private Options $options;

    /** @var bool $isVerbose Set true to display header/footer script message (name, time...) */
    protected bool $isVerbose = true;

    protected int $exitCode = 0;

    private Terminal $terminal;
    private Input $input;

    private Output $output;
    private Output $outputErr;

    /** @var array<string> $baseNamespaces Base namespaces for scripts class to execute. */
    protected array $baseNamespaces = ['Eureka\Component'];

    /**
     * @param ClockInterface $clock
     * @param array<int, string> $args List of arguments for current script to execute.
     * @param StreamInput|null $input
     * @param StreamOutput|null $output
     * @param StreamOutput|null $outputErr
     * @param ContainerInterface|null $container
     */
    public function __construct(
        private readonly ClockInterface $clock,
        array $args,
        ?StreamInput $input = null,
        ?StreamOutput $output = null,
        ?StreamOutput $outputErr = null,
        private readonly ?ContainerInterface $container = null,
    ) {
        $this->options = $this->initOptions();
        $this->options = (new OptionsParser($this->options))->parse($args);

        $isQuiet = (bool) $this->options->get('quiet')->getArgument();

        $this->input     = $input ?? new StreamInput(\STDIN);
        $this->output    = $output ?? new StreamOutput(\STDOUT, $isQuiet);
        $this->outputErr = $outputErr ?? new StreamOutput(\STDERR, $isQuiet);

        $this->terminal  = new Terminal($this->output);
    }

    public function getTerminal(): Terminal
    {
        return $this->terminal; // @codeCoverageIgnore
    }

    /**
     * Set base namespaces.
     * Those namespaces will be added as prefix to script name to autoload it.
     *
     * @param array<int, string> $baseNamespaces
     * @return $this
     */
    public function setBaseNamespaces(array $baseNamespaces = []): self
    {
        foreach ($baseNamespaces as $baseNamespace) {
            $this->baseNamespaces[] = trim($baseNamespace, '\\');
        }

        return $this;
    }

    private function initOptions(): Options
    {
        return (new Options())
            ->add(
                new Option(
                    shortName: 'h',
                    longName: 'help',
                    description: 'Display Help',
                ),
            )
            ->add(
                new Option(
                    longName: 'no-color',
                    description: 'Disable colors / styling (Can also be disabled with NO_COLOR env var)',
                ),
            )
            ->add(
                new Option(
                    longName: 'debug',
                    description: 'Activate debug mode (trace on exception if script is terminated with an exception)',
                    default: false,
                ),
            )
            ->add(
                new Option(
                    longName: 'time-limit',
                    description: 'Specified time limit in seconds (default: 0 - unlimited)',
                    hasArgument: true,
                    default: 0,
                ),
            )
            ->add(
                new Option(
                    longName: 'memory-limit',
                    description: 'Specified memory limit (128M, 1024M, 4G... - default: 256M)',
                    hasArgument: true,
                    default: '256M',
                ),
            )
            ->add(
                new Option(
                    longName: 'error-reporting',
                    description: 'Specified value for error-reporting (default: -1 - all)',
                    hasArgument: true,
                    default: -1,
                ),
            )
            ->add(
                new Option(
                    longName: 'error-display',
                    description: 'Specified value for display_errors setting. Values: 0|1 Default: 1 (display)',
                    hasArgument: true,
                    default: 1,
                ),
            )
            ->add(
                new Option(
                    longName: 'quiet',
                    description: 'Force disabled console output (if message are written on stream output)',
                    default: false,
                ),
            )
            ->add(
                new Option(
                    longName: 'with-header',
                    description: 'Enable console lib message header',
                    default: false,
                ),
            )
            ->add(
                new Option(
                    longName: 'with-footer',
                    description: 'Enable console lib messages footer',
                    default: false,
                ),
            )
            ->add(
                new Option(
                    longName: 'script',
                    description: 'Console class script to run (Example: database/console)',
                    mandatory: true,
                    hasArgument: true,
                ),
            )
        ;
    }

    /**
     * Display console lib help
     *
     * @return void
     */
    private function help(): void
    {
        $this->output->writeln(' *** RUN - HELP ***');
        $this->output->writeln('');

        (new Help('...', $this->options, $this->output))->display();
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
        //~ Init timer
        $this->time = -microtime(true);

        //~ Reporting all error (default: all error) !
        error_reporting((int) $this->options->get('error-reporting')->getArgument());
        ini_set('display_errors', (string) ((int) $this->options->get('error-display')->getArgument()));

        //~ Set limit time to 0 (default: unlimited) !
        set_time_limit((int) $this->options->get('time-limit')->getArgument());

        //~ Set memory limit
        ini_set('memory_limit', (string) $this->options->get('memory-limit')->getArgument());

        $this->isVerbose = !$this->options->get('quiet')->getArgument();
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

        $time  = round($this->time, 2);
        $date  = $this->clock->now()->format('Y-m-d H:i:s');

        $text = (new Style\Style())
            ->color(Bit4StandardColor::Green)
            ->apply(" *** END SCRIPT - Time taken: {$time}s - $date ***")
        ;

        if ($this->options->get('with-footer')->getArgument()) {
            $this->output->writeln($text);
        }
    }

    /**
     * Terminate script with correct execution code.
     *
     * @return never
     * @codeCoverageIgnore
     */
    public function terminate(): never
    {
        exit($this->exitCode);
    }

    /**
     * This method is main method for console lib.
     * - display console lib help
     * - OR display script help (if script name is defined)
     * - OR execute script
     *
     * @throws \Exception
     */
    public function run(): void
    {
        $script           = null;
        $beforeHasBeenRun = false;

        try {
            $scriptName = $this->getScriptName();
            $script     = $this->getScriptInstance($scriptName);
            $script->setStreams($this->input, $this->output, $this->outputErr);

            $this->handleHelp($scriptName, $script);

            $this->handleRun($scriptName, $script, $beforeHasBeenRun);
        } catch (StopAfterHelpException) {
            //~ Hard break, but continue to finally
        } catch (\Exception $exception) {
            $this->exitCode = 1;

            if (!$this->isVerbose) {
                throw $exception;
            }

            if ($this->logger instanceof LoggerInterface && !$exception instanceof Exception\AlreadyLoggedException) {
                $this->logger->error(
                    $exception->getMessage(),
                    ['exception' => $exception, 'type' => 'console.log'],
                ); // @codeCoverageIgnore
            }

            $text = (new Style\Style($this->options))
                ->color(Bit4StandardColor::Red)
                ->apply(" ~~ EXCEPTION[{$exception->getCode()}]: {$exception->getMessage()}")
            ;
            $this->output->writeln(PHP_EOL . $text);

            if ($this->options->get('debug')->getArgument()) {
                // @codeCoverageIgnoreStart
                $this->outputErr->writeln($exception->getFile());
                $this->outputErr->writeln((string) $exception->getLine());
                $this->outputErr->writeln($exception->getTraceAsString());
                // @codeCoverageIgnoreEnd
            }
        } finally {
            if ($beforeHasBeenRun && $script instanceof ScriptInterface) {
                // ~ Execute this method after execution of main script method.
                $script->after();
            }
        }
    }

    private function handleHelp(string $scriptName, ScriptInterface $script): void
    {
        if (!$this->options->get('help')->getArgument()) {
            return;
        }

        if ($this->options->get('with-header')->getArgument()) {
            $date = $this->clock->now()
                ->format('Y-m-d H:i:s')
            ;
            $text = (new Style\Style($this->options))
                ->color(Bit4StandardColor::Green)
                ->apply(" *** RUN - $scriptName - HELP - $date ***")
            ;

            $this->output->writeln($text);
        }
        $script->help();

        throw new StopAfterHelpException('help, stop!', 2001);
    }

    private function handleRun(
        string $scriptName,
        ScriptInterface $script,
        bool &$beforeHasBeenRun,
    ): void {
        // ~ Execute this method before starting main script method
        $script->before();

        $beforeHasBeenRun = true;

        // ~ Display header script only after execution of before method
        if ($this->options->get('with-header')->getArgument()) {
            $date = $this->clock->now()->format('Y-m-d H:i:s');
            $text = (new Style\Style($this->options))
                ->color(Bit4StandardColor::Green)
                ->apply(" *** RUN - $scriptName - $date ***")
            ;
            $this->output->writeln($text);
        }

        // ~ Execute main script method.
        $script->run();
    }

    private function getScriptName(): string
    {
        $name = $this->options->get('script')->getArgument();

        $scriptName = str_replace('/', '\\', ucwords((string) $name, '/\\'));

        // ~ Hook for console help
        if (empty($scriptName)) {
            $this->help();

            // ~ If no help asked, throw exception !
            if (!$this->options->get('help')->getArgument()) {
                throw new \UnexpectedValueException('Console Error: A script name must be provided!', 2000);
            }

            throw new StopAfterHelpException('help, stop!', 2001);
        }

        return $scriptName;
    }

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
            throw new \UnexpectedValueException("Current script class does not exists (script: '$scriptName') !", 2003);
        }

        return $className;
    }

    private function getScriptInstance(string $scriptName): ScriptInterface
    {
        $className = $this->getClassName($scriptName);

        try {
            if (empty($this->container)) {
                throw new \RuntimeException();
            }

            $script = $this->container->get(ltrim(strtr($className, '/', '\\'), '\\')); // @codeCoverageIgnore
        } catch (\Throwable) {
            $script = new $className();
        }

        if (!($script instanceof ScriptInterface)) {
            throw new \LogicException("Current script must implement ScriptInterface interface !", 2004);
        }

        if (!$script->executable()) {
            throw new \LogicException("Console Error: Script is not executable !", 2005); // @codeCoverageIgnore
        }

        return $script;
    }
}

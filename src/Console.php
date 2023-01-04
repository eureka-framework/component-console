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
use Eureka\Component\Console\Input\InputInterface;
use Eureka\Component\Console\Input\StreamInput;
use Eureka\Component\Console\Option\Option;
use Eureka\Component\Console\Option\OptionParser;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Output\OutputInterface;
use Eureka\Component\Console\Output\StreamOutput;
use Eureka\Component\Console\Terminal\Terminal;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
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

    protected ?ContainerInterface $container = null;

    protected int $exitCode = 0;

    private Terminal $terminal;
    private InputInterface $input;

    private OutputInterface $output;
    private OutputInterface $outputErr;

    /** @var array<string> $baseNamespaces Base namespaces for scripts class to execute. */
    protected array $baseNamespaces = ['Eureka\Component'];

    /**
     * @param array<string, string> $args List of arguments for current script to execute.
     * @param StreamInput|null $input
     * @param StreamOutput|null $output
     * @param StreamOutput|null $outputErr
     */
    public function __construct(
        array $args,
        ?StreamInput $input = null,
        ?StreamOutput $output = null,
        ?StreamOutput $outputErr = null
    ) {
        $this->options = $this->initOptions();
        $this->options = (new OptionParser($this->options))->parse($args);

        $isQuiet = (bool) $this->options->get('quiet')->getArgument();

        $this->input     = $input ?? new StreamInput(\STDIN);
        $this->output    = $output ?? new StreamOutput(\STDOUT, $isQuiet);
        $this->outputErr = $outputErr ?? new StreamOutput(\STDERR, $isQuiet);

        $this->terminal  = new Terminal($this->output);
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
                    shortName:   'h',
                    longName:    'help',
                    description: 'Display Help',
                )
            )
            ->add(
                new Option(
                    longName:    'no-color',
                    description: 'Disable colors / styling (Can also be disabled with NO_COLOR env var)',
                )
            )
            ->add(
                new Option(
                    longName:    'debug',
                    description: 'Activate debug mode (trace on exception if script is terminated with an exception)',
                    default:     false,
                )
            )
            ->add(
                new Option(
                    longName:    'time-limit',
                    description: 'Specified time limit in seconds (default: 0 - unlimited)',
                    hasArgument: true,
                    default:     0,
                )
            )
            ->add(
                new Option(
                    longName:    'memory-limit',
                    description: 'Specified memory limit (128M, 1024M, 4G... - default: 256M)',
                    hasArgument: true,
                    default:     '256M',
                )
            )
            ->add(
                new Option(
                    longName:    'error-reporting',
                    description: 'Specified value for error-reporting (default: -1 - all)',
                    hasArgument: true,
                    default:     -1,
                )
            )
            ->add(
                new Option(
                    longName:    'error-display',
                    description: 'Specified value for display_errors setting. Values: 0|1 Default: 1 (display)',
                    hasArgument: true,
                    default:     1,
                )
            )
            ->add(
                new Option(
                    longName:    'quiet',
                    description: 'Force disabled console lib messages (header, footer, timer...)',
                    default:     false,
                )
            )
            ->add(
                new Option(
                    longName:    'name',
                    description: 'Console class script to run (Example: database/console)',
                    mandatory:   true,
                    hasArgument: true,
                )
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
        $style = new Style\Style(' *** END SCRIPT - Time taken: ' . round($this->time, 2) . 's - ' . date('Y-m-d H:i:s') . ' ***');
        $style->color('fg', Style\Color::GREEN);

        if (!$this->arguments->has('script-no-header')) {
            Output\StreamOutput::std($style->get());
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
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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
            Output\StreamOutput::std(PHP_EOL . $style->get());

            if ($this->arguments->has('debug')) {
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
        if (!$this->arguments->has('help')) {
            return;
        }

        $style = new Style\Style();
        $style->setText(' *** RUN - ' . $scriptName . ' - HELP - ' . date('Y-m-d H:i:s') . ' ***');
        $style->color('fg', Style\Color::GREEN);

        Output\StreamOutput::std($style->get());
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
        if (!$this->arguments->has('script-no-header')) {
            $style = new Style\Style();
            $style->setText(' *** RUN - ' . $scriptName . ' - ' . date('Y-m-d H:i:s') . ' ***');
            $style->color('fg', Style\Color::GREEN);
            Output\StreamOutput::std($style->get());
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
        $name = $this->arguments->get('name', null, '');

        //~ Try to get default argument value if exists, to use it as a name.
        if (empty($name)) {
            $name = $this->arguments->get('__default__', null, '');
        }

        $scriptName = str_replace('/', '\\', ucwords((string) $name, '/\\'));

        // ~ Hook for console help
        if (empty($scriptName)) {
            $this->help();

            // ~ If no help asked, throw exception !
            if (!$this->arguments->has('help')) {
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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

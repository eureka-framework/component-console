<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console;

use Eureka\Component\Console\Color\Bit4HighColor;
use Eureka\Component\Console\Color\Bit4StandardColor;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Output\Output;
use Eureka\Component\Console\Style\Style;

/**
 * Help rendered for CLI
 *
 * @author Romain Cottard
 */
class Help
{
    public function __construct(
        private readonly string $scriptName,
        private readonly Options $declaredOptions,
        private readonly Output $output,
        private readonly Options $parsedOptions = new Options(),
    ) {}

    /**
     * Display help
     * Result example:
     *
     * -h, --help  Display Help
     *     --color Use color system for cli display
     *     --debug Use this argument to display trace for exceptions
     *
     * @return void
     */
    public function display(): void
    {
        $this->output->writeln('');

        $this->output->write(
            (new Style($this->parsedOptions))
                ->color(Bit4HighColor::Green)
                ->bold()
                ->apply('Use    : '),
        );

        $this->output->writeln(
            (new Style($this->parsedOptions))
                ->color(Bit4HighColor::White)
                ->bold()
                ->apply("bin/console $this->scriptName [OPTION]..."),
        );

        $this->output->writeln(
            (new Style($this->parsedOptions))
                ->color(Bit4StandardColor::Green)
                ->bold()
                ->apply('OPTIONS:'),
        );

        foreach ($this->declaredOptions as $option) {
            $line = '  ';

            if (!empty($option->getShortName())) {
                $line .= '-' . $option->getShortName();

                if ($option->hasArgument()) {
                    $line .= ' ARG';
                }

                $line .= ',';
            }

            $line = str_pad($line, 10); // add 8 space

            if (!empty($option->getLongName())) {
                $line .= '--' . $option->getLongName();
                if ($option->hasArgument()) {
                    $line .= '=ARG';
                }
            }

            $line = (new Style($this->parsedOptions))->bold()->apply(str_pad($line, 40));
            $line .= $option->getDescription();

            if ($option->isMandatory()) {
                $line .= (new Style($this->parsedOptions))->color(Bit4StandardColor::Red)->apply(' - MANDATORY');
            }

            $this->output->writeln($line);
        }

        $this->output->writeln('');
    }
}

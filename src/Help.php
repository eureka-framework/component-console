<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console;

use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Output\OutputInterface;
use Eureka\Component\Console\Style\OldColor;
use Eureka\Component\Console\Style\OldStyle;

/**
 * Help rendered for CLI
 *
 * @author Romain Cottard
 */
class Help
{
    public function __construct(
        private readonly string $scriptName,
        private readonly Options $options,
        private readonly OutputInterface $output,
    ) {
    }

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
        $style = new OldStyle();
        $this->output->writeln('');

        $this->output->write(
            $style
                ->setText('Use    : ')
                ->colorForeground(OldColor::GREEN)
                ->highlightForeground()
                ->bold()
                ->get()
        );

        $this->output->writeln(
            $style->reset()
            ->setText('bin/console ' . $this->scriptName . ' [OPTION]...')
            ->highlight('fg')
            ->bold()
            ->get()
        );

        $this->output->writeln(
            $style
                ->reset()
                ->setText('OPTIONS:')
                ->color('fg', OldColor::GREEN)
                ->bold()
                ->get()
        );

        foreach ($this->options as $option) {
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

            $line = $style->reset()->setText(str_pad($line, 40))->bold()->get();
            $line .= $option->getDescription();

            if ($option->isMandatory()) {
                $line .= $style->reset()->setText(' - MANDATORY')->colorForeground(OldColor::RED)->get();
            }

            $this->output->writeln($line);
        }

        $this->output->writeln('');
    }
}

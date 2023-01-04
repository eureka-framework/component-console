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
use Eureka\Component\Console\Style\Color;
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
        $style = new Style();
        Output\StreamOutput::std('');

        Output\StreamOutput::std($style->setText('Use    : ')->colorForeground(Color::GREEN)->highlightForeground()->bold()->get(), '');

        Output\StreamOutput::std(
            $style->reset()
            ->setText('bin/console ' . $this->scriptName . ' [OPTION]...')
            ->highlight('fg')
            ->bold()
            ->get()
        );

        Output\StreamOutput::std($style->reset()->setText('OPTIONS:')->color('fg', Color::GREEN)->bold()->get());

        foreach ($this->arguments as $argument) {
            $line = '  ';

            if (!empty($argument->shortName)) {
                $line .= '-' . $argument->shortName;

                if ($argument->hasValue) {
                    $line .= ' ARG';
                }

                $line .= ',';
            }

            $line = str_pad($line, 10); // add 8 space

            if (!empty($argument->fullName)) {
                $line .= '--' . $argument->fullName;
                if ($argument->hasValue) {
                    $line .= '=ARG';
                }
            }

            $line = $style->reset()->setText(str_pad($line, 40))->bold()->get();
            $line .= $argument->description;

            if ($argument->isMandatory) {
                $line .= $style->reset()->setText(' - MANDATORY')->colorForeground(Color::RED)->get();
            }

            Output\StreamOutput::std($line);
        }

        Output\StreamOutput::std('');
    }

    /**
     * Add argument in list for script help
     *
     * @param  string $shortName Short name for argument
     * @param  string $fullName Full name for argument
     * @param  string $description Argument's description
     * @param  bool $hasValue If argument must have value
     * @param  bool $isMandatory Set true to force mandatory mention.
     * @return $this
     */
    public function addArgument(
        string $shortName = '',
        string $fullName = '',
        string $description = '',
        bool $hasValue = false,
        bool $isMandatory = false
    ): self {
        $argument              = new \stdClass();
        $argument->shortName   = $shortName;
        $argument->fullName    = $fullName;
        $argument->description = $description;
        $argument->hasValue    = $hasValue;
        $argument->isMandatory = $isMandatory;

        $this->arguments[] = $argument;

        return $this;
    }
}

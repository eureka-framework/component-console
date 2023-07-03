<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Option;

class OptionParser
{
    public function __construct(private readonly Options $declaredOptions)
    {
    }

    /**
     * Parse argument from command lines.
     *
     * @param array<string>|null $argv CLI arguments.
     * @return Options
     */
    public function parse(array|null $argv = null): Options
    {
        $argv ??= ($_SERVER['argv'] ?? []);

        $options  = $this->declaredOptions;
        $iterator = new OptionsIterator($argv);

        foreach ($iterator as $name) {
            $isFull  = \str_starts_with($name, '--');
            $isShort = !$isFull && \str_starts_with($name, '-');
            $name    = $this->getName($name, $isFull, $isShort);

            $iterator->next();
            $value = ($iterator->valid() ? $iterator->current() : '');
            if (empty($value) || str_starts_with($value, '-')) {
                $value = true;
            }

            //~ Return to current position if not with case "-o ARG"
            if (!$isShort || $value === true) {
                $iterator->prev();
            }

            if ($isFull) {
                //~ Case --option[=ARG]
                $this->parseLong($options, $name, $value);
            } elseif ($isShort) {
                //~ Case -o [ARG] or -opt (ie: -o -p -t)
                $this->parseShort($options, $name, $value);
            } elseif ($iterator->key() !== 0 && $options->has('script')) {
                //~ Case of argument without option name, it is a shortcut for --script=ARG (for ScriptInterface)
                $options->get('script')->setArgument($name);
            }
        }

        return $options;
    }

    /**
     * @param string $name
     * @param bool $isFull
     * @param bool $isShort
     * @return string
     */
    private function getName(string $name, bool $isFull, bool $isShort): string
    {
        if ($isShort) {
            return \substr($name, 1);
        } elseif ($isFull) {
            return \substr($name, 2);
        }

        return $name;
    }

    /**
     * @param Options $options
     * @param string $name
     * @param string|bool $argument
     * @return void
     */
    private function parseLong(Options $options, string $name, string|bool $argument): void
    {
        $arg   = [];
        $match = \preg_match('`([0-9a-zA-Z_-]+)="?(.+)"?`', $name, $arg);

        if ($match > 0) {
            //~ Case '--option=argument'
            $name     = $arg[1];
            $argument = $arg[2];
        } //~ Otherwise, it is cse '--option'

        if ($this->declaredOptions->has($name)) {
            $option = $this->declaredOptions->get($name)->setArgument($argument);
        } else {
            $option = (new Option(longName: $name))->setArgument($argument);
        }

        $options->add($option);
    }

    /**
     * @param Options $options
     * @param string $name
     * @param string|bool $argument
     * @return void
     */
    private function parseShort(Options $options, string $name, string|bool $argument): void
    {
        $len = \strlen($name);

        //~ Handle case '-o' and case '-opt' (equivalent to -o -p -t)
        for ($letter = 0; $letter < $len; $letter++) {
            if ($this->declaredOptions->has($name)) {
                $option = $this->declaredOptions->get($name[$letter])->setArgument($argument);
            } else {
                $option = (new Option(longName: $name[$letter]))->setArgument($argument);
            }

            $options->add($option);
        }
    }
}

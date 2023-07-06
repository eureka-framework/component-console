<?php

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\AbstractScript;
use Eureka\Component\Console\Help;
use Eureka\Component\Console\Option\Option;
use Eureka\Component\Console\Option\Options;

class ExampleScript extends AbstractScript
{
    public function __construct()
    {
        $this->setExecutable();
        $this->setDescription('Example script');

        $this->initOptions(
            (new Options())
                ->add(
                    new Option(
                        shortName:   'u',
                        longName:    'user',
                        description: 'User name',
                        mandatory:   true,
                        hasArgument: true,
                        default:     'Joe doe',
                    )
                )
                ->add(
                    new Option(
                        shortName:   'n',
                        longName:    'is-night',
                        description: 'If is night'
                    )
                )
        );
    }

    public function help(): void
    {
        (new Help(self::class, $this->declaredOptions(), $this->output(), $this->options()))
            ->display()
        ;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $user = $this->options()->get('user', 'u')->getArgument();
        $say  = $this->options()->get('is-night', 'n')->getArgument() ? 'Good night' : 'Hello';

        $this->output()->writeln("$say $user!");
    }
}

<?php

/*
 * Copyright (c) Deezer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Terminal;

use Eureka\Component\Console\Exception\ShellException;

class Shell
{
    /**
     * @param string $command
     * @return string|null
     * @codeCoverageIgnore
     */
    public function exec(string $command): string|null
    {
        $result = \shell_exec($command);

        if ($result === false) {
            throw new ShellException('Pipe cannot be established!');
        }

        return $result;
    }
}

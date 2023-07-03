<?php

/*
 * Copyright (c) Deezer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

// Save existing tty configuration
$term = `stty -g`;
echo $term . "\n";

system("stty -icanon");
echo "Press Arrow or q to quit: ";
while ($c = fread(STDIN, 1)) {
    echo "Read from STDIN: " . $c . "\n";

    if ($c === "q") {
        break;
    }
}
system("stty '" . $term . "'");

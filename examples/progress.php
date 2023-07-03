<?php

/*
 * Copyright (c) Deezer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\Color\Bit8StandardColor;
use Eureka\Component\Console\Option\OptionParser;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Output\StreamOutput;
use Eureka\Component\Console\Progress\ProgressBar;
use Eureka\Component\Console\Progress\ProgressPercent;
use Eureka\Component\Console\Terminal\Terminal;

require_once __DIR__ . '/../vendor/autoload.php';

$terminal = new Terminal(new StreamOutput(\STDOUT, false));
$options  = (new OptionParser(new Options()))->parse($argv);

$progress = new ProgressBar($options, 1000, 100, '#', ' ');
echo "\n";
for ($i = 1; $i <= 750; $i++) {
    echo $progress->inc()->render((string) $i) . "\r";
}
echo "\n";

$progress = new ProgressBar($options, 1000, 100, '|', ' ');
echo "\n";
for ($i = 1; $i <= 750; $i++) {
    echo $progress->inc()->render((string) $i) . "\r";
}
echo "\n";

$progress = new ProgressBar($options, 1000, 100);
echo "\n";
for ($i = 1; $i <= 750; $i++) {
    echo $progress->inc()->render((string) $i) . "\r";
}
echo "\n";

$purple = Bit8StandardColor::Magenta;
$progress = new ProgressBar($options, 1000, 100);
echo "\n";
for ($i = 1; $i <= 750; $i++) {
    echo $progress->inc()->render((string) $i, $purple) . "\r";
}

echo "\n";
$progress = new ProgressBar($options, 1000, 0, terminal: $terminal);
echo "\n";
for ($i = 1; $i <= 750; $i++) {
    echo $progress->inc()->render((string) $i, $purple) . "\r";
}
echo "\n";

$progress = new ProgressPercent($options, 1000, true);
echo "\n";
for ($i = 1; $i <= 750; $i++) {
    echo $progress->inc()->render((string) $i) . "\r";
    usleep(1_000);
}
echo "\n";

<?php

/*
 * Copyright (c) Deezer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\Color\Bit8HighColor;
use Eureka\Component\Console\Color\Bit8StandardColor;
use Eureka\Component\Console\Style\Style;

require_once __DIR__ . '/../vendor/autoload.php';

echo (new Style())->bold()->apply('Bold text') . "\n";
echo (new Style())->faint()->apply('Light text') . "\n";
echo (new Style())->italic()->apply('Italic text') . "\n";
echo (new Style())->underline()->apply('Underline text') . "\n";
echo (new Style())->blink()->apply('Blink (slow) text') . "\n";
echo (new Style())->blink(true, true)->apply('Blink (fast) text') . "\n";
echo (new Style())->invert()->apply('Invert') . "\n";
echo (new Style())->strike()->apply('Strike') . "\n";

echo (new Style())->color(Bit8StandardColor::Cyan)->apply('Cyan Color') . "\n";
echo (new Style())->color(Bit8StandardColor::Cyan)->bold()->apply('Bold Cyan Color') . "\n";
echo (new Style())->color(Bit8StandardColor::Cyan)->faint()->apply('Light Cyan Color') . "\n";
echo (new Style())->color(Bit8HighColor::Cyan)->apply('High Cyan Color') . "\n";
echo (new Style())->background(Bit8StandardColor::Red)->apply('Red Background') . "\n";

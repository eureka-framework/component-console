<?php

/*
 * Copyright (c) Deezer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\Color\Bit24RGBColor;
use Eureka\Component\Console\Color\Bit4HighColor;
use Eureka\Component\Console\Color\Bit4StandardColor;
use Eureka\Component\Console\Color\Bit8GreyscaleColor;
use Eureka\Component\Console\Color\Bit8RGBColor;
use Eureka\Component\Console\Color\Bit8StandardColor;
use Eureka\Component\Console\Color\Bit8HighColor;
use Eureka\Component\Console\Style\Style;

require_once __DIR__ . '/../vendor/autoload.php';

echo "4 Bits colors\n";
echo "-------------\n";

/** @var Bit4StandardColor[]|Bit4HighColor[] $colors */
$colors = [
    Bit4StandardColor::Black,
    Bit4StandardColor::Red,
    Bit4StandardColor::Green,
    Bit4StandardColor::Yellow,
    Bit4StandardColor::Blue,
    Bit4StandardColor::Magenta,
    Bit4StandardColor::Cyan,
    Bit4StandardColor::White,
    Bit4HighColor::Black,
    Bit4HighColor::Red,
    Bit4HighColor::Green,
    Bit4HighColor::Yellow,
    Bit4HighColor::Blue,
    Bit4HighColor::Magenta,
    Bit4HighColor::Cyan,
    Bit4HighColor::White,
];

echo "Standard & High intensity Colors:\n";
foreach ($colors as $color) {
    $text = str_pad(str_pad((string) $color->value, 3), 5, ' ', STR_PAD_BOTH);
    $text = (new Style())->background($color)->apply($text);

    echo $text;
}

echo "\n";

echo "\n";
echo "8 Bits colors\n";
echo "-------------\n";
/** @var Bit8StandardColor[]|Bit8HighColor[] $colors */
$colors = [
    Bit8StandardColor::Black,
    Bit8StandardColor::Red,
    Bit8StandardColor::Green,
    Bit8StandardColor::Yellow,
    Bit8StandardColor::Blue,
    Bit8StandardColor::Magenta,
    Bit8StandardColor::Cyan,
    Bit8StandardColor::White,
    Bit8HighColor::Black,
    Bit8HighColor::Red,
    Bit8HighColor::Green,
    Bit8HighColor::Yellow,
    Bit8HighColor::Blue,
    Bit8HighColor::Magenta,
    Bit8HighColor::Cyan,
    Bit8HighColor::White,
];

echo "Standard & High intensity Colors:\n";
foreach ($colors as $color) {
    $text = str_pad(str_pad((string) $color->value, 3), 5, ' ', STR_PAD_BOTH);
    $text = (new Style())->background($color)->apply($text);

    echo $text;
}

echo "\n";

echo "RGB Colors:\n";
for ($r = 0; $r < 6; $r++) {
    for ($g = 0; $g < 6; $g++) {
        for ($b = 0; $b < 6; $b++) {
            $color = new Bit8RGBColor($r, $g, $b);
            $text = str_pad(str_pad((string) $color->getIndex(), 3), 5, ' ', STR_PAD_BOTH);
            $text = (new Style())->background($color)->apply($text);

            echo $text;
        }
    }
    echo "\n";
}

echo "\n";

echo "Greyscale Colors:\n";
for ($intensity = 0; $intensity < 24; $intensity++) {
    $color = new Bit8GreyscaleColor($intensity);
    $text  = str_pad((string) $color->getIndex(), 5, ' ', STR_PAD_BOTH);
    $text  = (new Style())->background($color)->apply($text);

    echo $text;
}

echo "\n";

echo "\n";
echo "24 Bits colors (only (r;0;0), (0;g;0), (0;0;b) lines are drawn for the test)\n";
echo "--------------\n";

for ($r = 0; $r < 256; $r++) {
    $color = new Bit24RGBColor($r, 0, 0);
    $text = (new Style())->background($color)->apply(' ');

    echo $text;
}
echo "\n";

for ($g = 0; $g < 256; $g++) {
    $color = new Bit24RGBColor(0, $g, 0);
    $text = (new Style())->background($color)->apply(' ');

    echo $text;
}
echo "\n";

for ($b = 0; $b < 256; $b++) {
    $color = new Bit24RGBColor(0, 0, $b);
    $text = (new Style())->background($color)->apply(' ');

    echo $text;
}
echo "\n";

var_export(opcache_get_configuration());
var_export(opcache_get_status()['jit']);

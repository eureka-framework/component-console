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
use Eureka\Component\Console\Option\OptionsParser;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Style\CellStyle;
use Eureka\Component\Console\Style\Style;
use Eureka\Component\Console\Table\Align;
use Eureka\Component\Console\Table\Border;
use Eureka\Component\Console\Table\Cell;
use Eureka\Component\Console\Table\Column;
use Eureka\Component\Console\Table\Table;

require_once __DIR__ . '/../vendor/autoload.php';

//~ Options
$options = (new OptionsParser(new Options()))->parse($argv);

//~ Color Styles
$green   = (new Style($options))->color(Bit8StandardColor::Green);
$red     = (new Style($options))->color(Bit8StandardColor::Red);
$black   = (new Style($options))->color(Bit8StandardColor::Black);

//~ Simple table
$table = new Table(3);
$table->newRow([1, 2, 3]);
$table->newRow([4, 5, 6]);
echo $table->render();

//~ Simple table with long text
$table = new Table(3);
$table->newRow([1, 2, 3]);
$table->newRow(["text", "very long text", 1.2]);
echo $table->render();

//~ Table with header
$table = new Table(3);
$table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
$table->newRow([1, 2, 3]);
$table->newRow(["text", "very long text", 1.2]);
echo $table->render();

//~ Complexe table with multiple header & spans
$table = new Table(3);
$table->newRowSpan('Table With Span Header', true);
$table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
$table->newRow([1, 2, 3]);
$table->newRow(["text", "very long text", 1.2]);
$table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
$table->newRow([1, 2, 3]);
$table->newRow(["text", "very long text", 1.2]);
$table->newRowSpan('Inner Span Row #1');
$table->newRowSpan('Inner Span Row #2', style: new CellStyle(options: $options, align: Align::Right));
$table->newRow([1, 2, 3]);
$table->newRow(["text", "very long text", 1.2]);
$table->newRowSpan('Inner Span Header', true);
$table->newRow(["text", "very long text", 1.2]);
$table->newRowSpan('Bottom Span Header', true);
echo $table->render();

//~ Table colored green borders
$table = new Table(3, new Border(style: $green));
$table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
$table->newRow([1, 2, 3]);
$table->newRow(["text", "very long text", 1.2]);
echo $table->render();

//~ Table colored red last column & underline last line
$table = new Table(
    [
        new Column(),
        new Column(),
        new Column((new CellStyle($options, align: Align::Center))->background(Bit8StandardColor::Red))
    ],
    new Border(style: $green)
);
$cell = new Cell(1.2, (new CellStyle($options, align: Align::Right))->background(Bit8StandardColor::Cyan)->bold());
$table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
$table->newRow([1, 2, 3]);
$table->newRow(["text", "very long text", $cell], false, (new CellStyle($options))->underline());
echo $table->render();

//~ Table colored red last column & underline last line but ascii base border
$table = new Table(
    [
        new Column(),
        new Column(),
        new Column((new CellStyle($options, align: Align::Center))->background(Bit8StandardColor::Red))
    ],
    new Border(Border::BASE, style: $green)
);
$cell = new Cell(1.2, (new CellStyle($options, align: Align::Right))->background(Bit8StandardColor::Cyan)->bold());
$table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
$table->newRow([1, 2, 3]);
$table->newRow(["text", "very long text", $cell], false, (new CellStyle($options))->underline());
echo $table->render();

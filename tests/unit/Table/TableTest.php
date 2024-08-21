<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Table;

use Eureka\Component\Console\Color\Bit8StandardColor;
use Eureka\Component\Console\Option\Option;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Style\CellStyle;
use Eureka\Component\Console\Style\Style;
use Eureka\Component\Console\Table\Align;
use Eureka\Component\Console\Table\Border;
use Eureka\Component\Console\Table\Cell;
use Eureka\Component\Console\Table\Column;
use Eureka\Component\Console\Table\Row;
use Eureka\Component\Console\Table\Table;
use Eureka\Component\Console\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    public function testICanGetBasicTable(): void
    {
        //~ Given
        $expected = <<<TABLE
        ╔══════════╤══════════╤══════════╗
        ║ 1        │ 2        │ 3        ║
        ║ 4        │ 5        │ 6        ║
        ╚══════════╧══════════╧══════════╝
        
        TABLE;


        //~ When
        $table   = new Table(3);
        $table->newRow([1, 2, 3]);
        $table->newRow([4, 5, 6]);

        //~ Then
        $this->assertSame($expected, $table->render());
    }

    public function testICanGetBasicTableWithTruncatedText(): void
    {
        //~ Given
        $options = new Options();
        $expected = <<<TABLE
        ╔══════════╤══════════╤══════════╗
        ║ 1        │ 2        │ 3        ║
        ║ text     │ very lo… │ 1.2      ║
        ╚══════════╧══════════╧══════════╝
        
        TABLE;

        //~ When
        $table   = new Table(3);
        $table->newRow([1, 2, 3]);
        $table->newRow(["text", "very long text", 1.2]);

        //~ Then
        $this->assertSame($expected, $table->render());
    }

    public function testICanGetBasicTableWithHeader(): void
    {
        //~ Given
        $options = new Options();
        $expected = <<<TABLE
        ╔══════════╤══════════╤══════════╗
        ║ Col 1    │ Col 2    │ Col 3    ║
        ╠══════════╪══════════╪══════════╣
        ║ 1        │ 2        │ 3        ║
        ║ text     │ very lo… │ 1.2      ║
        ╚══════════╧══════════╧══════════╝

        TABLE;

        //~ When
        $table   = new Table(3);
        $table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
        $table->newRow([1, 2, 3]);
        $table->newRow(["text", "very long text", 1.2]);

        //~ Then
        $this->assertSame($expected, $table->render());
    }

    public function testICanGetComplexTable(): void
    {
        //~ Given
        $expected = <<<TABLE
        ╔════════════════════════════════╗
        ║     Table With Span Header     ║
        ╠══════════╤══════════╤══════════╣
        ║ Col 1    │ Col 2    │ Col 3    ║
        ╠══════════╪══════════╪══════════╣
        ║ 1        │ 2        │ 3        ║
        ║ text     │ very lo… │ 1.2      ║
        ╠══════════╪══════════╪══════════╣
        ║ Col 1    │ Col 2    │ Col 3    ║
        ╠══════════╪══════════╪══════════╣
        ║ 1        │ 2        │ 3        ║
        ║ text     │ very lo… │ 1.2      ║
        ║       Inner Span Row #1        ║
        ║              Inner Span Row #2 ║
        ║ 1        │ 2        │ 3        ║
        ║ text     │ very lo… │ 1.2      ║
        ╠══════════╧══════════╧══════════╣
        ║       Inner Span Header        ║
        ╠══════════╤══════════╤══════════╣
        ║ text     │ very lo… │ 1.2      ║
        ╠══════════╧══════════╧══════════╣
        ║       Bottom Span Header       ║
        ╚════════════════════════════════╝

        TABLE;

        //~ When
        $table   = new Table(3);
        $table->newRowSpan('Table With Span Header', true);
        $table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
        $table->newRow([1, 2, 3]);
        $table->newRow(["text", "very long text", 1.2]);
        $table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
        $table->addRow((new Row())->newCell(1)->newCell(2)->newCell(3));
        $table->newRow(["text", "very long text", 1.2]);
        $table->newRowSpan(new Cell('Inner Span Row #1'));
        $table->newRowSpan('Inner Span Row #2', style: new CellStyle(align: Align::Right));
        $table->newRow([1, 2, 3]);
        $table->newRow(["text", "very long text", 1.2]);
        $table->newRowSpan('Inner Span Header', true);
        $table->newRow(["text", "very long text", 1.2]);
        $table->newRowSpan('Bottom Span Header', true);

        //~ Then
        $this->assertSame($expected, $table->render());
    }

    public function testICanGetComplexTableWithStyle(): void
    {
        //~ Given
        $expected = <<<TABLE
        [38;5;2m╔[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m╤[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m╤[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m╗[0m
        [38;5;2m║[0m Col 1    [38;5;2m│[0m Col 2    [38;5;2m│[0m[48;5;1m  Col 3   [0m[38;5;2m║[0m
        [38;5;2m╠[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m╪[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m╪[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m╣[0m
        [38;5;2m║[0m 1        [38;5;2m│[0m 2        [38;5;2m│[0m[48;5;1m    3     [0m[38;5;2m║[0m
        [38;5;2m║[0m[4m text     [0m[38;5;2m│[0m[4m very lo… [0m[38;5;2m│[0m[48;5;6m[4m[1m      1.2 [0m[38;5;2m║[0m
        [38;5;2m╚[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m╧[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m╧[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m═[0m[38;5;2m╝[0m

        TABLE;

        //~ When
        $table = new Table(
            [
                new Column(),
                new Column(),
                new Column((new CellStyle(align: Align::Center))->background(Bit8StandardColor::Red)),
            ],
            new Border(style: (new Style())->color(Bit8StandardColor::Green)),
        );
        $cell = new Cell(1.2, (new CellStyle(align: Align::Right))->background(Bit8StandardColor::Cyan)->bold());
        $table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
        $table->newRow([1, 2, 3]);
        $table->newRow(["text", "very long text", $cell], false, (new CellStyle())->underline());

        //~ Then
        $this->assertSame($expected, $table->render());
    }

    public function testICanGetComplexTableWithStyleAndNoColorOption(): void
    {
        //~ Given
        $options  = (new Options())->add(new Option('no-color', default: true));
        $expected = <<<TABLE
        ╔══════════╤══════════╤══════════╗
        ║ Col 1    │ Col 2    │  Col 3   ║
        ╠══════════╪══════════╪══════════╣
        ║ 1        │ 2        │    3     ║
        ║[4m text     [0m│[4m very lo… [0m│[4m      1.2 [0m║
        ╚══════════╧══════════╧══════════╝

        TABLE;

        //~ When
        $table = new Table(
            [
                new Column(),
                new Column(),
                new Column((new CellStyle($options, align: Align::Center))->background(Bit8StandardColor::Red)),
            ],
            new Border(style: (new Style($options))->color(Bit8StandardColor::Green)),
        );
        $cell = new Cell(1.2, (new CellStyle($options, align: Align::Right))->background(Bit8StandardColor::Cyan));
        $table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
        $table->newRow([1, 2, 3]);
        $table->newRow(["text", "very long text", $cell], false, (new CellStyle($options))->underline());

        //~ Then
        $this->assertSame($expected, $table->render());
    }
}

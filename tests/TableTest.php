<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests;

use Eureka\Component\Console\Style\OldStyle;
use Eureka\Component\Console\Table\BorderStyle;
use Eureka\Component\Console\Table\Column;
use Eureka\Component\Console\Table\Table;
use PHPUnit\Framework\TestCase;

/**
 * Class to test tables
 *
 * @author Romain Cottard
 * @group unit
 */
class TableTest extends TestCase
{
    public function testICanRenderSimpleTableInAscii(): void
    {
        $columns = [
            new Column('col1'),
            new Column('col2'),
            new Column('col3'),
        ];

        $table = new Table($columns);
        $table->addRow(['1', '2', '3'], false, (new OldStyle())->bold());
        $table->addRowSpan(['1', '2', '3']);

        $assert = <<<ASSERT
+----------+----------+----------+
|   col1   |   col2   |   col3   |
+----------+----------+----------+
|    1     |    2     |    3     |
|           1 - 2 - 3            |
+----------+----------+----------+
ASSERT;

        $display = (string) $table;

        $this->assertEquals($assert, $display);
    }

    public function testICanRenderDoubleExternalBorderTableInUnicode(): void
    {
        $columns = [
            new Column('col1'),
            new Column('col2'),
            new Column('col3'),
        ];

        $table = new Table($columns, true, BorderStyle::UNICODE);
        $table->addRow(['1', '2', '3'], false, (new OldStyle())->bold());
        $table->addRowSpan(['1', '2', '3']);

        $assert = <<<ASSERT
╔══════════╤══════════╤══════════╗
║   col1   │   col2   │   col3   ║
╠══════════╪══════════╪══════════╣
║    1     │    2     │    3     ║
║           1 - 2 - 3            ║
╚══════════╧══════════╧══════════╝
ASSERT;

        $display = (string) $table;

        $this->assertEquals($assert, $display);
    }

    public function testICanRenderSimpleBarInUnicode(): void
    {
        $columns = [
            new Column('col1'),
            new Column('col2'),
            new Column('col3'),
        ];

        $table = new Table($columns, false, BorderStyle::UNICODE);
        $table->addBar(BorderStyle::DOUBLE_TOP);
        $table->addBar();

        $assert = <<<ASSERT
╔══════════╤══════════╤══════════╗
╟──────────┼──────────┼──────────╢
╚══════════╧══════════╧══════════╝
ASSERT;

        $display = (string) $table;

        $this->assertEquals($assert, $display);
    }

    /**
     * @return void
     * @dataProvider stringToPadProvider
     */
    public function testICanStrPadUnicodeText(
        string $string,
        int $padSize,
        int $padDir,
        string $expected
    ): void {
        $string = Table::strPadUnicode($string, $padSize, '.', $padDir);

        $this->assertEquals($expected, $string);
    }

    /**
     * @return array<string,array<int|string>>
     */
    public function stringToPadProvider(): array
    {
        return [
            'no pad'    => ['no pad', 3, STR_PAD_RIGHT, 'no pad'],
            'pad right' => ['pad', 5, STR_PAD_RIGHT, 'pad..'],
            'pad left'  => ['pad', 5, STR_PAD_LEFT, '..pad'],
            'pad both'  => ['böth!', 10, STR_PAD_BOTH, '..böth!...'],
        ];
    }
}

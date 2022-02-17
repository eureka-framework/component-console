<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests;

use Eureka\Component\Console\Argument\Argument;
use Eureka\Component\Console\Style\Style;
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
    public function testProgressTypeBarWithProgressArgument(): void
    {
        $columns = [
            new Column('col1'),
            new Column('col2'),
            new Column('col3'),
        ];

        $table = new Table($columns);
        $table->addRow(['1', '2', '3'], false, (new Style())->bold());
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
}

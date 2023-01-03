# Component Console (formerly [Eurekon](https://github.com/eureka-framework/Eurekon))

[![Current version](https://img.shields.io/packagist/v/eureka/component-console.svg?logo=composer)](https://packagist.org/packages/eureka/component-console)
[![Supported PHP version](https://img.shields.io/static/v1?logo=php&label=PHP&message=7.4|8.0|8.1|8.2&color=777bb4)](https://packagist.org/packages/eureka/component-console)
![CI](https://github.com/eureka-framework/component-console/workflows/CI/badge.svg)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=eureka-framework_component-console&metric=alert_status)](https://sonarcloud.io/dashboard?id=eureka-framework_component-console)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=eureka-framework_component-console&metric=coverage)](https://sonarcloud.io/dashboard?id=eureka-framework_component-console)


Console component to run script.
You can integrate it easily in Eureka Framework application with Kernel Console, and use dependency injection.

Console provide argument manager (like every another command on linux system).
Console have some defined classes to help you to do a lot of beautiful script.

## Execution

```bash
my-app/$ vendor/bin/console --name="My\Class\Name"
my-app/$ vendor/bin/console --name=My/Class/Script
my-app/$ vendor/bin/console My/Class/Script
my-app/$ vendor/bin/console my/class/script # first character of each part can omit upper case
```

## Documentation

### Arguments
The arguments work like unix arguments.
Full & short alias are supported.

So, you can have dynamics script based on command arguments.

```php
<?php

namespace Application\My\Script;

use Eureka\Component\Console\AbstractScript;
use Eureka\Component\Console\Argument\Argument;
use Eureka\Component\Console\Help;
use Eureka\Component\Console\IO\Out;
use Eureka\Component\Console\ScriptInterface;

/**
 * Class ExampleScript
 *
 * @author Romain Cottard
 */
class ExampleScript extends AbstractScript implements ScriptInterface
{
    public function __construct()
    {
        $this->setExecutable();
        $this->setDescription('Example script');
    }

    /**
     * @return void
     */
    public function help(): void
    {
        $help = new Help(self::class);
        $help->addArgument('u', 'user', 'User name', true, false);
        $help->addArgument('n', 'is-night', false, false);
        $help->display();
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $arguments = Argument::getInstance();
        
        $user = $arguments->get('user', 'u', 'joe doe');
        $say  = $arguments->has('is-night') ? 'Good night' : 'Hello';

        Out::std("$say $user!");
    }
}
```

### Reserved Arguments

Some arguments are reserved to 
 * `--help`: Display help (global when no script name is passed, script help otherwise).
 * `--color`: Display styles when styling is used in code. 
 * `--debug`: Display trace when exception is thrown
 * `--quiet`: Disable console output. Work only when Out::std() & Out::err() are used to display content.
 * `--progress`: Enable pretty display of progress bar / %. Without this option, usage of `Progress`class will have no effect. 
 * `--time-limit=ARG`: By default, the time is unlimited. But sometime, you need to specify a maximum execution time.
 * `--memory-limit=ARG`: Default value is 256M. You can adapt here according to your needs
 * `--error-reporting=ARG`: By default, all errors are reported. You can adapt here.
 * `--error-display=ARG`: By default, all errors are reported. You can adapt here.
 * `--name=ARG`

### Help

An class help is provided to have a pretty format of parameters when you use `--help` arguments for your script.
```php

use Eureka\Component\Console\Help;

$help = new Help('Application/My/Script');
$help->addArgument('u', 'user', 'User name', true, false);
$help->addArgument('', 'is-night', 'Use to force the night', false, false);
$help->display();
```

```bash
 *** RUN - Application\My\Script - HELP - 2020-10-15 12:24:34 ***

Use    : bin/console Application/My/Script [OPTION]...
OPTIONS:
  -h,     --help                        Reserved - Display Help
  -u ARG, --user=ARG                    User name
          --is-night                    Use to force the night

 *** END SCRIPT - Time taken: 0.05s - 2020-10-15 12:24:34 ***
```


### Display
Two output method are available for display.
By default, the content is send directly to STDOUT & STDERR streams.

```php
<?php

use Eureka\Component\Console\IO\Out;

Out::std('Hello!'); // by default, PHP_EOL is added to the line
Out::std('Hello!', ''); // No new line

Out::err('Error message for "error output (stderr)'); // same as std(), new line is added by default.
```

To capture the output (with ob_start()), you need to allow buffering.
When you activate the buffering, the basic "echo" is used instead of write on STDOUT / STDERR.

```php
Out::allowBuffering(true);
```


### Styling
Console output support styling and color:

```php
<?php

use Eureka\Component\Console\Style;

$whiteBold      = (new Style\Style())->bold();
$greenHighlight = (new Style\Style())->highlightForeground()->colorForeground(Style\Color::GREEN);
$bgErrorRed     = (new Style\Style())->colorForeground(Style\Color::RED);

echo (string) $bgErrorRed->setText('An error as occurred!');
```


### Pretty table
You can easily display a pretty table with Table & related classes:

```php
<?php

use Eureka\Component\Console\Table\Table;
use Eureka\Component\Console\Table\Column;

$columns = [
    new Column('col1'),
    new Column('col2'),
    new Column('col3'),
];

$table = new Table($columns);
$table->addRow(['1', '2', '3'], false, (new Style())->bold());
$table->addRowSpan(['1', '2', '3']);
$table->display();
```

The output will be:
```
+----------+----------+----------+
|   col1   |   col2   |   col3   |
+----------+----------+----------+
|    1     |    2     |    3     |
|           1 - 2 - 3            |
+----------+----------+----------+
```

And with Unicode option for Border style, output will be:
```
╔══════════╤══════════╤══════════╗
║   col1   │   col2   │   col3   ║
╠══════════╪══════════╪══════════╣
║    1     │    2     │    3     ║
║           1 - 2 - 3            ║
╚══════════╧══════════╧══════════╝
```

### Pretty progress bar / % / time
You can easily display a progress bar, percentage or estimated time left:

```php
<?php

use Eureka\Component\Console\Progress\Progress;

$maxIteration = 10;
$progress = new Progress('phpunit', $maxIteration);
$progress->setTypeDisplay(Progress::TYPE_BAR);

for ($i = 0; $i < $maxIteration; $i++) {
    $progress->display('iteration #' . $i);
}

$progress->displayComplete('done');
```

#### Type bar
After the first iteration:
```bash
[#####                                             ] iteration #0',
```

After the second iteration:
```bash
[##########                                        ] iteration #1',
```

At the end:
```bash
[##################################################] done
```

#### Type percent
After the first iteration:
```bash
[ 10.00%] iteration #0',
```

After the second iteration:
```bash
[ 20.00%] iteration #1',
```

At the end:
```bash
[100.00%] done
```

#### Type time
After the first iteration:
```bash
  > phpunit - [ 10%] [    1/    3 sec] - iteration #0
```

After the second iteration:
```bash
  > phpunit - [ 20%] [    2/    4 sec] - iteration #1
```

At the end:
```bash
  > phpunit - [100%] - done !
```

## About

### License

component-console is licensed under the MIT License - see the `LICENSE` file for details

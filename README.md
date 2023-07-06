# Component Console (formerly [Eurekon](https://github.com/eureka-framework/Eurekon))

[![Current version](https://img.shields.io/packagist/v/eureka/component-console.svg?logo=composer)](https://packagist.org/packages/eureka/component-console)
[![Supported PHP version](https://img.shields.io/static/v1?logo=php&label=PHP&message=8.1%20-%208.2&color=777bb4)](https://packagist.org/packages/eureka/component-console)
![CI](https://github.com/eureka-framework/component-console/workflows/CI/badge.svg)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=eureka-framework_component-console&metric=alert_status)](https://sonarcloud.io/dashboard?id=eureka-framework_component-console)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=eureka-framework_component-console&metric=coverage)](https://sonarcloud.io/dashboard?id=eureka-framework_component-console)


Console component to run script.
You can integrate it easily in Eureka Framework application with Kernel Console, and use dependency injection.

Console provide argument manager (like every another command on linux system).
Console have some defined classes to help you to do a lot of beautiful script.

## Execution

```bash
vendor/bin/console --script="My\Class\Name"
vendor/bin/console --script=My/Class/Script
vendor/bin/console My/Class/Script
vendor/bin/console my/class/script # first character of each part can omit upper case
```

## Documentation

### Options & Arguments
The options work like unix arguments.
Full & short alias are supported.

So, you can have dynamics script based on command options.

```php
<?php

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\AbstractScript;
use Eureka\Component\Console\Help;
use Eureka\Component\Console\Option\Option;
use Eureka\Component\Console\Option\Options;

class ExampleScript extends AbstractScript
{
    public function __construct()
    {
        $this->setExecutable();
        $this->setDescription('Example script');

        $this->initOptions(
            (new Options())
                ->add(
                    new Option(
                        shortName:   'u',
                        longName:    'user',
                        description: 'User name',
                        mandatory:   true,
                        hasArgument: true,
                        default:     'Joe doe',
                    )
                )
                ->add(
                    new Option(
                        shortName:   'n',
                        longName:    'is-night',
                        description: 'If is night'
                    )
                )
        );
    }

    public function help(): void
    {
        (new Help(self::class, $this->declaredOptions(), $this->output(), $this->options()))
            ->display()
        ;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $user = $this->options()->get('user', 'u')->getArgument();
        $say  = $this->options()->get('is-night', 'n')->getArgument() ? 'Good night' : 'Hello';
        
        //~ Can also use short version:
        // $user = $this->options()->value('user', 'u');
        // $say  = $this->options()->value('is-night', 'n') ? 'Good night' : 'Hello';

        $this->output()->writeln("$say $user!");
    }
}

```

### Reserved Options

Some options are reserved:
* `-h`, `--help`: Display Help
* `--no-color`: Disable colors / styling (Can also be disabled with NO_COLOR env var)
* `--debug`: Activate debug mode (trace on exception if script is terminated with an exception)
* `--time-limit=ARG`: Specified time limit in seconds (default: 0 - unlimited)
* `--memory-limit=ARG`: Specified memory limit (128M, 1024M, 4G... - default: 256M)
* `--error-reporting=ARG`: Specified value for error-reporting (default: -1 - all)
* `--error-display=ARG`: Specified value for display_errors setting. Values: 0|1 Default: 1 (display)
* `--quiet`: Force disabled console output (if message are written on stream output)
* `--with-header`: Enable console lib message header
* `--with-footer`: Enable console lib messages footer
* `--script=ARG` (or just `ARG`): Console class script to run (Example: database/console) - MANDATORY

### Help

Class help is provided to have a pretty format of parameters when you use `--help` arguments for your script.
```php
<?php

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\Help;
use Eureka\Component\Console\Option\Option;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Output\NullOutput;

$output  = new NullOutput();
$options = (new Options())
    ->add(
        new Option(
            shortName:   'u',
            longName:    'user',
            description: 'User name',
            mandatory:   true,
            hasArgument: true,
            default:     'Joe doe',
        )
    )
    ->add(
        new Option(
            shortName:   'n',
            longName:    'is-night',
            description: 'If is night'
        )
    )
);
$help = new Help('Application/My/Script', $options, new NullOutput());
$help->display();
```

```bash

Use    : bin/console Examples\ExampleScript [OPTION]...
OPTIONS:
  -u ARG, --user=ARG                    User name - MANDATORY
  -n,     --is-night                    If is night

```


### Input / Output

#### Input
Input is handle by Input interface. Can handle prompt console from user.

### Output
Output is handle by Output interface.
Can display content on standard output, file, memory (depending the stream resource passed to the OutputStream class).

```php
<?php

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\Output\StreamOutput;

$output = new StreamOutput(\STDOUT, false);
$output->writeln('Hello!'); // PHP_EOL is added to the line
$output->write('Hello!');   // No new line

$output = new StreamOutput(\STDERR, false);
$output->writeln('Error message for "error output (stderr)');
```

### Styling
Console output support styling and color:

```php
<?php

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\Color\Bit4HighColor;
use Eureka\Component\Console\Color\Bit4StandardColor;
use Eureka\Component\Console\Style\Style;

$whiteBold      = (new Style())->bold();
$greenHighlight = (new Style())->color(Bit4StandardColor::Green);
$bgErrorRed     = (new Style())->background(Bit4HighColor::Red);

echo $bgErrorRed->apply('An error as occurred!');
```

### Colors
Console now embed 4 bits colors, 8 bits colors & 24 bits colors.
Support of 8 bit & 24 bits colors depends on terminal application (8 bit color have correct support, not 24 bits)
- 4 bits color are listed in two Enum to facilitate manipulation (for regular & high intensity color)
- 8 bits base colors are listed in two Enum (for regular & high intensity color)
- 8 bits complex color use RGB class (+ greyscale class with intensity)
- 24 bits use RGB class

### Pretty table
You can easily display a pretty table with Table & related classes:

```php
<?php

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\Table\Table;

//~ Table with header
$table = new Table(3, new Border(Border::BASE);
$table->newRow(['Col 1', 'Col 2', 'Col 3'], true);
$table->newRow([1, 2, 3]);
$table->newRow(["text", "very long text", 1.2]);
$table->newRowSpan('Spanned row');
echo $table->render();
```

The output will be:
```
╔══════════╤══════════╤══════════╗
║ Col 1    │ Col 2    │ Col 3    ║
╠══════════╪══════════╪══════════╣
║ 1        │ 2        │ 3        ║
║ text     │ very lo… │ 1.2      ║
║          Spanned row           ║
╚════════════════════════════════╝
```

And with non-extended ascii chars:
```
+----------+----------+----------+
| Col 1    | Col 2    | Col 3    |
+----------+----------+----------+
| 1        | 2        | 3        |
| text     | very lo… | 1.2      |
|          Spanned row           |
+--------------------------------+
```

### Pretty progress bar / % 
You can easily display a progress bar or percentage:

```php
<?php

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\Progress\ProgressBar;

$maxIteration = 10;
$maxSize      = 20;
$progress = new ProgressBar(new Options(), $maxIteration, $maxIteration);
$progress->setTypeDisplay(ProgressOld::TYPE_BAR);

for ($i = 0; $i < $maxIteration; $i++) {
    $progress->display('iteration #' . $i);
}

```

#### Type bar
After the first iteration:
```bash
│██░░░░░░░░░░░░░░░░░░│ iteration #0
```

After the second iteration:
```bash
│████░░░░░░░░░░░░░░░░│ iteration #1
```

At the end:
```bash
│████████████████████│ iteration #9
```

#### Type percent
After the first iteration:
```bash
[ 10.00%] iteration #0
```

After the second iteration:
```bash
[ 20.00%] iteration #1
```

At the end:
```bash
[100.00%] iteration #9
```

### Terminal & Cursor
Now, you can get a terminal to have information about width / height of terminal, and manipulate cursor on this

```php
<?php

declare(strict_types=1);

namespace Examples;

use Eureka\Component\Console\Output\NullOutput;use Eureka\Component\Console\Output\StreamOutput;use Eureka\Component\Console\Terminal\Terminal;

$output   = new StreamOutput(\STDOUT, false);
$terminal = new Terminal(new NullOutput());

//~ Get Terminal sizes
$output->writeln("{$terminal->getWidth()}x{$terminal->getHeight()}");

//~ Clear terminal
$terminal->clear();

//~ Get cursor and manipulate it
$terminal->cursor()->down();
```



## Contributing

See the [CONTRIBUTING](CONTRIBUTING.md) file.


### Install / update project

You can install project with the following command:
```bash
make install
```

And update with the following command:
```bash
make update
```

NB: For the components, the `composer.lock` file is not committed.

### Testing & CI (Continuous Integration)

#### Tests
You can run tests (with coverage) on your side with following command:
```bash
make tests
```

For prettier output (but without coverage), you can use the following command:
```bash
make testdox # run tests without coverage reports but with prettified output
```

#### Code Style
You also can run code style check with following commands:
```bash
make phpcs
```

You also can run code style fixes with following commands:
```bash
make phpcbf
```

#### Static Analysis
To perform a static analyze of your code (with phpstan, lvl 9 at default), you can use the following command:
```bash
make phpstan
```

To ensure you code still compatible with current supported version at Deezer and futures versions of php, you need to
run the following commands (both are required for full support):

Minimal supported version:
```bash
make php81compatibility
```

Maximal supported version:
```bash
make php82compatibility
```

#### CI Simulation
And the last "helper" commands, you can run before commit and push, is:
```bash
make ci  
```

## License

component-console is licensed under the MIT License - see the `LICENSE` file for details

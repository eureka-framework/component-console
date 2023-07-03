<?php

/*
 * Copyright (c) Deezer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

function keypress(): string
{
    $input  = '';
    $read   = [STDIN];
    $write  = null;
    $except = null;

    readline_callback_handler_install('', function() { /* Nothing here */ });

    do {
        $input .= fgetc(STDIN);
    } while(stream_select($read, $write, $except, 0, 1));

    readline_callback_handler_remove();

    return $input;

}

$csi = "\033[";
enum Key: string
{
    case Enter = "\x0A";
    case Esc = "\x1B";
    case Space = "\x20";
    case Up = "\x1B\x5B\x41";
    case Down = "\x1B\x5B\x42";
    case Right = "\x1B\x5B\x43";
    case Left = "\x1B\x5B\x44";

    public static function match(string $key): string
    {
        return match ($key) {
            Key::Up->value => Key::Up->name,
            Key::Down->value => Key::Down->name,
            Key::Left->value => Key::Left->name,
            Key::Right->value => Key::Right->name,
            Key::Esc->value => Key::Esc->name,
            Key::Space->value => Key::Space->name,
            Key::Enter->value => Key::Enter->name,
            default => $key
        };
    }

    public static function toHex(string $key): string
    {
        $chars = (array) unpack("C*", $key);
        array_map(
            strtoupper(...),
            array_map(
                dechex(...),
                (array) unpack("C*", $key)
            )
        );

        foreach ($chars as $index => $dec) {
            $hex = str_pad(strtoupper(dechex($dec)), 2, '0', STR_PAD_LEFT);
            $chars[$index] = '\x' . $hex;
        }

        return implode('', $chars);
    }
}


while (true) {
    $key = keypress();

    echo Key::toHex($key) . "\n";
    $match = Key::match($key);

    echo "Key: $match\n";

    if ($key === 'q' || $key === Key::Esc->value) {
        break;
    }
}

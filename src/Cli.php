<?php

declare(strict_types=1);

namespace Millipede;

use Iterator;

use const PHP_OS;

/**
 * A class to generate a Millipede in PHP.
 */
final class Cli
{
    /**
     * POSIX color.
     *
     * @var array<string>
     */
    private const COLOR_LIST = ['white', 'red', 'yellow', 'green', 'cyan', 'blue', 'magenta'];

    /** @var array<string> */
    private array $colorOffsets;

    /**
     * Create a Cli Renderer to Display the millipede in Rainbow.
     */
    public static function createFromRandom(): self
    {
        return new self(self::COLOR_LIST[array_rand(self::COLOR_LIST)]);
    }

    /**
     * Create a Cli Renderer to Display the millipede in Rainbow.
     */
    public static function createFromRainbow(): self
    {
        return new self(...self::COLOR_LIST);
    }

    /**
     * a new instance.
     */
    public function __construct(string ...$colorIndex)
    {
        $colorIndex = array_filter(
            array_map(strtolower(...), $colorIndex),
            fn (string $value): bool => in_array($value, self::COLOR_LIST, true)
        );

        if ([] === $colorIndex) {
            $colorIndex = ['white'];
        }

        $this->colorOffsets = $colorIndex;
    }

    /**
     * Modifier the renderer output.
     */
    public function __invoke(Renderer $renderer): Iterator
    {
        $nbColors = count($this->colorOffsets);
        foreach ($renderer as $key => $part) {
            yield self::outln("<<{$this->colorOffsets[$key % $nbColors]}>>$part<<reset>>");
        }
    }

    /**
     * Format the text output
     * Inspired by Aura\Cli\Stdio\Formatter (https://github.com/auraphp/Aura.Cli).
     */
    public static function outln(string $str): string
    {
        return self::out($str).PHP_EOL;
    }

    /**
     * Format the text output
     * Inspired by Aura\Cli\Stdio\Formatter (https://github.com/auraphp/Aura.Cli).
     */
    public static function out(string $str): string
    {
        /** @var callable|string $formatter */
        static $formatter;
        /** @var ?Closure $func */
        static $func;
        /** @var ?string $regex */
        static $regex;
        /** @var array<string, string> $codes */
        static $codes = [
            'reset'      => '0',
            'bold'       => '1',
            'dim'        => '2',
            'underscore' => '4',
            'blink'      => '5',
            'reverse'    => '7',
            'hidden'     => '8',
            'black'      => '30',
            'red'        => '31',
            'green'      => '32',
            'yellow'     => '33',
            'blue'       => '34',
            'magenta'    => '35',
            'cyan'       => '36',
            'white'      => '37',
            'blackbg'    => '40',
            'redbg'      => '41',
            'greenbg'    => '42',
            'yellowbg'   => '43',
            'bluebg'     => '44',
            'magentabg'  => '45',
            'cyanbg'     => '46',
            'whitebg'    => '47',
        ];

        if (null === $regex) {
            $regex = ',<<\s*((('.implode('|', array_keys($codes)).')(\s*))+)>>,Umsi';
            $func = preg_replace(...);
            $formatter = '';
            if (!str_contains(PHP_OS, 'WIN')) {
                $func = preg_replace_callback(...);
                $formatter = fn (array $matches) => chr(27).'['.strtr(preg_replace('/(\s+)/msi', ';', $matches[1]), $codes).'m';
            }
        }

        return ' '.$func($regex, $formatter, $str);
    }
}

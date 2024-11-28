<?php

namespace Millipede;

use PHPUnit\Framework\TestCase;

class CliTest extends TestCase
{
    public function testFormat(): void
    {
        $esc = chr(27);
        $text = '<<bold>>bold%percent<<reset>>';
        $expect = " {$esc}[1mbold%percent{$esc}[0m".PHP_EOL;
        $actual = Cli::outln($text);
        self::assertSame($expect, $actual);
    }

    public function testInvoke(): void
    {
        self::assertCount(23, iterator_to_array((new Cli())(new Renderer())));
    }
}

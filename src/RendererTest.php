<?php

namespace Millipede;

use PHPUnit\Framework\TestCase;

class RendererTest extends TestCase
{
    public function testSimpleUsage(): void
    {
        $expected = <<<EOF

 Hello World!

    ╚⊙ ⊙╝
 ╚═(███)═╝
  ╚═(███)═╝
   ╚═(███)═╝
    ╚═(███)═╝
   ╚═(███)═╝


EOF;
        $millipede = (new Millipede())
            ->curve(4)
            ->size(5)
            ->comment('Hello World!');
        self::assertSame($expected, (string) new Renderer($millipede));
    }

    public function testSimpleUsageWithoutCurve(): void
    {
        $expected = <<<EOF

 Hello World!

  ╚⊙ ⊙╝
╚═(███)═╝
╚═(███)═╝
╚═(███)═╝
╚═(███)═╝
╚═(███)═╝


EOF;
        $millipede = (new Millipede())
            ->curve(0)
            ->size(5)
            ->comment('Hello World!');
        self::assertSame($expected, (string) new Renderer($millipede));
    }


    public function testComplexUsage(): void
    {
        $expected = <<<EOF

   ╔═(███████)═╗
    ╔═(███████)═╗
   ╔═(███████)═╗
  ╔═(███████)═╗
 ╔═(███████)═╗
╔═(███████)═╗
 ╔═(███████)═╗
  ╔═(███████)═╗
   ╔═(███████)═╗
    ╔═(███████)═╗
       ╔⊙     ⊙╗

 Hello World!


EOF;
        $millipede = (new Millipede())
            ->curve(4)
            ->size(10)
            ->comment('Hello World!')
            ->opposite(true)
            ->reverse(true)
            ->width(7);

        self::assertSame($expected, (string) new Renderer($millipede));
    }
}

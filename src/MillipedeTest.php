<?php

declare(strict_types=1);

namespace Millipede;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MillipedeTest extends TestCase
{
    private Millipede $millipede;

    public function setUp(): void
    {
        $this->millipede = new Millipede();
    }

    public function testNewInstance(): void
    {
        self::assertSame('', $this->millipede->comment);
        self::assertSame(20, $this->millipede->size);
        self::assertSame(3, $this->millipede->width);
        self::assertSame(4, $this->millipede->curve);
        self::assertSame(' ', $this->millipede->head);
        self::assertSame('█', $this->millipede->skin);
        self::assertFalse($this->millipede->isOpposite);
        self::assertFalse($this->millipede->isReverse);
    }

    #[DataProvider('providerSetSize')]
    public function testSetSize(int $size, int $expected): void
    {
        $newSize = $this->millipede->size($size)->size;
        self::assertSame($expected, $newSize);
    }

    /**
     * @return array<string, array<int>>
     */
    public static function providerSetSize(): array
    {
        return [
            '0 size' => [0, 20],
            'negative size' => [-23, 20],
            'basic usage' => [23, 23],
        ];
    }

    #[DataProvider('providerSetComment')]
    public function testSetComment(string $comment, string $expected): void
    {
        self::assertSame($expected, $this->millipede->comment($comment)->comment);
    }

    /**
     * @return array<string, array<string>>
     */
    public static function providerSetComment(): array
    {
        return [
            'empty string' => ['', ''],
            'basic usage' => ['Hello World!', 'Hello World!'],
        ];
    }

    #[DataProvider('providerSetCurve')]
    public function testSetCurve(int $size, int $expected): void
    {
        self::assertSame($expected, $this->millipede->curve($size)->curve);
    }

    /**
     * @return array<string, array<int>>
     */
    public static function providerSetCurve(): array
    {
        return [
            '0 size' => [0, 0],
            'negative size' => [-23, 4],
            'basic usage' => [23, 23],
        ];
    }

    #[DataProvider('providerSetWidth')]
    public function testSetWidth(int $size, int $expected): void
    {
        self::assertSame($expected, $this->millipede->width($size)->width);
    }

    /**
     * @return array<string, array<int>>
     */
    public static function providerSetWidth(): array
    {
        return [
            '0 size' => [0, 3],
            'negative size' => [-23, 3],
            'basic usage' => [23, 23],
        ];
    }

    #[DataProvider('providerWithReverse')]
    public function testWithOpposite(bool $size, bool $expected): void
    {
        self::assertSame($expected, $this->millipede->opposite($size)->isOpposite);
    }

    #[DataProvider('providerWithReverse')]
    public function testWithReverse(bool $size, bool $expected): void
    {
        self::assertSame($expected, $this->millipede->reverse($size)->isReverse);
    }

    /**
     * @return array<array<bool>>
     */
    public static function providerWithReverse(): array
    {
        return [
            [true, true],
            [false, false],
        ];
    }

    #[DataProvider('providerChars')]
    public function testWithSkin(string $char, string $expected): void
    {
        self::assertSame($expected, $this->millipede->skin($char)->skin);
    }

    #[DataProvider('providerChars')]
    public function testWithHeadBlock(string $char, string $expected): void
    {
        self::assertSame($expected, $this->millipede->head($char)->head);
    }

    /**
     * @return array<array<string>>
     */
    public static function providerChars(): array
    {
        return [
            ['#', '#'],
            ["\t", "\t"],
            ['€', '€'],
            ['█', '█'],
            [' ', ' '],
            ['\uD83D\uDE00', '😀'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function providerInvalidChars(): array
    {
        return [
            ['foobar'],
            ['\uD83D\uDE00\uD83D\uDE00'],
        ];
    }

    #[DataProvider('providerInvalidChars')]
    public function testWithHeadBlockThrowsInvalidArgumentException(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->millipede->head($input);
    }

    #[DataProvider('providerInvalidChars')]
    public function testWithSkinThrowsInvalidArgumentException(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->millipede->skin($input);
    }
}

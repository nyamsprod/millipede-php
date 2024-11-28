<?php

declare(strict_types=1);

namespace Millipede;

use Iterator;
use IteratorAggregate;
use Stringable;

/**
 * A class to generate a Millipede in PHP.
 * @implements IteratorAggregate<array-key, string>
 */
final class Renderer implements IteratorAggregate, Stringable
{
    /**
     * Millipede padding offsets.
     *
     * @var array<string>
     */
    private array $paddingOffsets = [''];
    private string $headUp = '╚⊙ ⊙╝';
    private string $headDown = '╔⊙ ⊙╗';
    private string $bodyUp = '╚═(███)═╝';
    private string $bodyDown = '╔═(███)═╗';
    private string $head;
    private string $body;
    private int $maxCurve;

    public function __construct(private readonly Millipede $millipede = new Millipede())
    {
        $head = $this->headUp;
        if ($this->millipede->isReverse) {
            $head = $this->headDown;
        }

        $repeat = $this->millipede->curve;
        if (0 === $repeat) {
            $repeat = 2; //to align the head with the body
        }

        $this->head = str_repeat(' ', $repeat).str_replace(
            ' ',
            str_repeat($this->millipede->head, $this->millipede->width - 2),
            $head
        );

        $body = $this->bodyUp;
        if ($this->millipede->isReverse) {
            $body = $this->bodyDown;
        }

        $this->body = str_replace(
            '███',
            str_repeat($this->millipede->skin, $this->millipede->width),
            $body
        );

        $this->maxCurve = $this->millipede->curve * 2;
        if (0 < $this->millipede->curve) {
            for ($index = 1; $index <= $this->maxCurve; ++$index) {
                $delta = $index % $this->maxCurve;
                $size = min($delta, $this->maxCurve - $delta);
                $this->paddingOffsets[] = str_repeat(' ', $size);
            }
        }
    }

    public function __toString()
    {
        ob_start();
        foreach ($this as $part) {
            echo $part, PHP_EOL;
        }

        return (string) ob_get_clean();
    }

    /**
     * @return Iterator<array-key, string>
     */
    public function getIterator(): Iterator
    {
        yield '';

        $comment = $this->millipede->comment;
        if (!$this->millipede->isReverse && '' !== $comment) {
            yield ' '.$comment;
            yield '';
        }

        for ($offset = 0, $size = $this->millipede->size; $offset <= $size; ++$offset) {
            yield $this->getPart($offset);
        }

        if ($this->millipede->isReverse && '' !== $comment) {
            yield '';
            yield ' '.$comment;
        }

        yield '';
    }

    /**
     * Return a Millipede part according to the given offset.
     */
    private function getPart(int $offset): string
    {
        if ($this->millipede->isReverse) {
            $offset = $this->millipede->size - $offset;
        }

        $content = $this->body;
        if (0 === $offset) {
            $content = $this->head;
        }

        return $this->getPadding($offset).$content;
    }

    /**
     * Retrieve the Padding offset depending on the iteration.
     */
    private function getPadding(int $offset): string
    {
        $curve = $this->millipede->curve;
        if (0 === $curve) {
            return '';
        }

        if ($this->millipede->isOpposite) {
            $offset += $curve - 1;
        }

        return $this->paddingOffsets[$offset % $this->maxCurve];
    }
}

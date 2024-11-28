<?php

declare(strict_types=1);

namespace Millipede;

use InvalidArgumentException;

/**
 * A class to configure the Millipede settings.
 */
final class Millipede
{
    private const REGEXP_UNICODE = '/\\\\u(?<unicode>[0-9A-F]{1,4})/i';

    public function __construct(
        public readonly string $comment = '',
        public readonly int $size = 20,
        public readonly int $width = 3,
        public readonly int $curve = 4,
        public readonly bool $isReverse = false,
        public readonly bool $isOpposite = false,
        public readonly string $head = ' ',
        public readonly string $skin = '█',
    ) {
    }

    /**
     * return an Array representation of the Config object.
     *
     * @return array<string, string|bool|int>
     */
    public function toArray(): array
    {
        return [
            'head' => $this->head,
            'skin' => $this->skin,
            'width' => $this->width,
            'size' => $this->size,
            'curve' => $this->curve,
            'comment' => $this->comment,
            'reverse' => $this->isReverse,
            'opposite' => $this->isOpposite,
        ];
    }

    /**
     * Return an instance with the specified comment.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified comment string.
     */
    public function comment(string $comment): self
    {
        if ($comment === $this->comment) {
            return $this;
        }

        return new self($comment, $this->size, $this->width, $this->curve, $this->isReverse, $this->isOpposite, $this->head, $this->skin);
    }

    /**
     * Return an instance with the specified size.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified size.
     */
    public function size(int $size): self
    {
        if ($size < 1) {
            $size = 20;
        }

        if ($size === $this->size) {
            return $this;
        }

        return new self($this->comment, $size, $this->width, $this->curve, $this->isReverse, $this->isOpposite, $this->head, $this->skin);
    }

    /**
     * Return an instance with the specified curve.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified curve.
     */
    public function curve(int $curve): self
    {
        if ($curve < 0) {
            $curve = 4;
        }

        if ($curve === $this->curve) {
            return $this;
        }

        return new self($this->comment, $this->size, $this->width, $curve, $this->isReverse, $this->isOpposite, $this->head, $this->skin);
    }

    /**
     * Return an instance with the specified reverse state.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified reverse.
     */
    public function reverse(bool $reverse): self
    {
        if ($reverse === $this->isReverse) {
            return $this;
        }

        return new self($this->comment, $this->size, $this->width, $this->curve, $reverse, $this->isOpposite, $this->head, $this->skin);
    }

    /**
     * Return an instance with the specified opposite state.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified opposite.
     */
    public function opposite(bool $opposite): self
    {
        if ($opposite === $this->isOpposite) {
            return $this;
        }

        return new self($this->comment, $this->size, $this->width, $this->curve, $this->isReverse, $opposite, $this->head, $this->skin);
    }

    /**
     * Return an instance with the specified curve.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified width.
     */
    public function width(int $width): self
    {
        if ($width < 3) {
            $width = 3;
        }

        if ($width === $this->width) {
            return $this;
        }

        return new self($this->comment, $this->size, $width, $this->curve, $this->isReverse, $this->isOpposite, $this->head, $this->skin);
    }

    /**
     * Return an instance with the specified body block.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified skin.
     *
     * @throws InvalidArgumentException If $skin is not a single character
     */
    public function skin(string $skin): self
    {
        $skin = $this->filterPattern($skin, 'skin');
        if ($skin === $this->skin) {
            return $this;
        }

        return new self($this->comment, $this->size, $this->width, $this->curve, $this->isReverse, $this->isOpposite, $this->head, $skin);
    }

    /**
     * Return an instance with the head pattern.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified head.
     *
     * @throws InvalidArgumentException If $head is not a single character
     */
    public function head(string $head): self
    {
        $head = $this->filterPattern($head, 'head');
        if ($head === $this->head) {
            return $this;
        }

        return new self($this->comment, $this->size, $this->width, $this->curve, $this->isReverse, $this->isOpposite, $head, $this->skin);
    }

    /**
     * Tell whether the submitted string is a valid block character.
     *
     * @throws InvalidArgumentException if the pattern is invalid
     */
    private function filterPattern(string $str, string $part): string
    {
        return match (true) {
            1 === mb_strlen($str) => $str,
            1 === preg_match(self::REGEXP_UNICODE, $str) => $this->filterUnicodeCharacter($str),
            default => throw new InvalidArgumentException(sprintf('The %s pattern must be a single character', $part)),
        };
    }

    /**
     * decode unicode characters.
     *
     * @see http://stackoverflow.com/a/37415135/2316257
     *
     * @throws InvalidArgumentException if the character is not valid
     */
    private function filterUnicodeCharacter(string $str): string
    {
        $replaced = (string) preg_replace(self::REGEXP_UNICODE, '&#x$1;', $str);
        $result = mb_convert_encoding(
            mb_convert_encoding($replaced, 'UTF-16', 'HTML-ENTITIES'),
            'UTF-8',
            'UTF-16'
        );

        return 1 === mb_strlen($result) ? $result : throw new InvalidArgumentException('The given string `'.$str.'` is not a valid unicode string');
    }
}

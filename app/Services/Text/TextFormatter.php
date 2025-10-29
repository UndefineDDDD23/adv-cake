<?php

namespace App\Services\Text;

use App\Contracts\Text\TextFormatterInterface;

class TextFormatter implements TextFormatterInterface
{
    public function reverseWords(string $text): string
    {
        $result = '';
        $length = mb_strlen($text, 'UTF-8');
        $i = 0;

        while ($i < $length) {
            $char = mb_substr($text, $i, 1, 'UTF-8');

            if ($this->isLetter($char)) {
                [$segment, $i] = $this->collectLetterRun($text, $i);
                $result .= $this->reverseSegmentPreservingCase($segment);
                continue;
            }

            $result .= $char;
            $i++;
        }

        return $result;
    }

    /**
     * Checks whether a character is a letter in any alphabet.
     */
    protected function isLetter(string $char): bool
    {
        return (bool) preg_match('/\p{L}/u', $char);
    }

    /**
     * Collects a sequence of consecutive letters starting from $i.
     * Returns [segmentString, newIndex].
     */
    protected function collectLetterRun(string $text, int $i): array
    {
        $run = '';
        $length = mb_strlen($text, 'UTF-8');

        while ($i < $length) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            if (!$this->isLetter($char)) {
                break;
            }

            $run .= $char;
            $i++;
        }

        return [$run, $i];
    }

    /**
     * Reverses a word-like segment while preserving uppercase/lowercase positions.
     */
    protected function reverseSegmentPreservingCase(string $segment): string
    {
        $letters = preg_split('//u', $segment, -1, PREG_SPLIT_NO_EMPTY);
        $caseMask = array_map(fn($ch) => $this->isUpper($ch), $letters);
        $reversed = array_reverse($letters);

        $rebuilt = '';
        foreach ($reversed as $pos => $char) {
            $rebuilt .= $caseMask[$pos]
                ? mb_strtoupper($char, 'UTF-8')
                : mb_strtolower($char, 'UTF-8');
        }

        return $rebuilt;
    }

    /**
     * Determines if a character is uppercase.
     */
    protected function isUpper(string $char): bool
    {
        return mb_strtoupper($char, 'UTF-8') === $char && mb_strtolower($char, 'UTF-8') !== $char;
    }
}

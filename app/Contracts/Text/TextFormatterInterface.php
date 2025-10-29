<?php

namespace App\Contracts\Text;

interface TextFormatterInterface
{
    public function reverseWords(string $input): string;
}
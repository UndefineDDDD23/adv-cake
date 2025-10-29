<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Text\TextFormatter;

class TextFormatterTest extends TestCase
{
    private TextFormatter $textService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->textService = new TextFormatter();
    }

    /**
     * @dataProvider wordsProvider
     */
    public function test_reverse_words(array $cases): void
    {
        foreach ($cases as $input => $expected) {
            $result = $this->textService->reverseWords($input);
            $this->assertSame(
                expected: $expected,
                actual:   $result,
                message:  "Failed asserting that '{$input}' → '{$expected}' (got '{$result}')"
            );
        }
    }

    public static function wordsProvider(): array
    {
        return [
            'english basic' => [[
                'Cat'            => 'Tac',
                'houSe'          => 'esuOh',
                'elEpHant'       => 'tnAhPele',
                'it is eng WorD' => 'ti si gne DroW',
            ]],
            'russian basic' => [[
                'Мышь'             => 'Ьшым',
                'домИК'            => 'кимОД',
                'сЛово на русСком' => 'оВолс ан мокСсур',
            ]],
            'punctuation' => [[
                'cat,'               => 'tac,',
                'Зима:'              => 'Амиз:',
                "is 'cold' now"      => "si 'dloc' won",
                'это «Так» "просто"' => 'отэ «Кат» "отсорп"',
                'third-part'         => 'driht-trap',
                "can`t"              => "nac`t",
                'Hello, world!'      => 'Olleh, dlrow!',
            ]],
        ];
    }
}

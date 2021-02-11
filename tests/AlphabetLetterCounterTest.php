<?php

namespace tests\main;

use Generator;
use main\Dictionary;
use main\AlphabetLetterCounter;
use PHPUnit\Framework\TestCase;
use SplFixedArray;

class AlphabetLetterCounterTest extends TestCase
{
    /**
     * @dataProvider processProvider
     *
     * @param string $alphabetStr
     * @param bool $isGetContentShouldReturn
     * @param array $dictionaryFromArray
     * @param int $maxWordCount
     * @param array $expected
     */
    public function testProcess(
        string $alphabetStr,
        bool $isGetContentShouldReturn,
        array $dictionaryFromArray,
        int $maxWordCount,
        array $expected
    ) {
        $dictionaryMock = $this->createDictionaryMock(
            $this->exactly(strlen($alphabetStr)),
            $isGetContentShouldReturn,
            $dictionaryFromArray
        );
        $letterCounter = new AlphabetLetterCounter($maxWordCount, $alphabetStr, $dictionaryMock);
        $actual = $letterCounter->process();
        $this->assertEquals($expected, $actual);
    }

    private function createDictionaryMock(
        $phpUnitGetContentExpectsCount,
        bool $isGetContentShouldReturn = false,
        array $dictionaryFromArray = []
    ) {
        $dictionaryMock = $this->createMock(Dictionary::class);

        if ($isGetContentShouldReturn) {
            $willReturn = SplFixedArray::fromArray($dictionaryFromArray);
            $dictionaryMock->expects($phpUnitGetContentExpectsCount)
                ->method('getContent')
                ->willReturn($willReturn);
        } else {
            $dictionaryMock->expects($phpUnitGetContentExpectsCount)
                ->method('getContent');
        }

        return $dictionaryMock;
    }

    public function processProvider(): Generator
    {
        yield 'returns all matched words when maxWordCount > all matched words' => [
            'alphabetStr' => 'AB',
            'isGetContentShouldReturn' => true,
            'dictionaryFromArray' => ['ANY', 'ANIMAL', 'SPARTA', 'BEAR'],
            'maxWordCount' => 10,
            'expected' => [
                'A' => [
                    'count' => 2,
                    'words' => ['ANIMAL', 'SPARTA'],
                ],
                'B' => [
                    'count' => 1,
                    'words' => ['BEAR'],
                ],
            ],
        ];

        yield "doesn't return all matched words when maxWordCount < all matched words" => [
            'alphabetStr' => 'AB',
            'isGetContentShouldReturn' => true,
            'dictionaryFromArray' => ['ANY', 'ANIMAL', 'SPARTA', 'BEAR'],
            'maxWordCount' => 1,
            'expected' => [
                'A' => [
                    'count' => 2,
                    'words' => ['ANIMAL'],
                ],
                'B' => [
                    'count' => 1,
                    'words' => ['BEAR'],
                ],
            ],
        ];

        yield 'returns empty array when alphabetStr is empty' => [
            'alphabetStr' => '',
            'isGetContentShouldReturn' => false,
            'dictionaryFromArray' => [],
            'maxWordCount' => 100,
            'expected' => [],
        ];

        yield 'returns array that contains default data for letter when dictionary content is empty' => [
            'alphabetStr' => 'A',
            'isGetContentShouldReturn' => true,
            'dictionaryFromArray' => [],
            'maxWordCount' => 10,
            'expected' => [
                'A' => [
                    'count' => 0,
                    'words' => [],
                ],
            ],
        ];

        yield 'rejects words which strlen > MAX_AMOUNT_LETTERS_IN_WORD constant' => [
            'alphabetStr' => 'AB',
            'isGetContentShouldReturn' => true,
            'dictionaryFromArray' => ['ANY', 'ANIMAL', 'SPARTA', 'BEAR', 'ABRACADABRA-ABRACADABRA'],
            'maxWordCount' => 10,
            'expected' => [
                'A' => [
                    'count' => 2,
                    'words' => ['ANIMAL', 'SPARTA'],
                ],
                'B' => [
                    'count' => 1,
                    'words' => ['BEAR'],
                ],
            ],
        ];
    }
}

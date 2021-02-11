<?php

namespace tests\main\utils;

use main\utils\AlphabetUtils;
use PHPUnit\Framework\TestCase;

class AlphabetUtilsTest extends TestCase
{
    private AlphabetUtils $utils;

    protected function setUp(): void
    {
        $this->utils = new AlphabetUtils();
    }

    public function testEmptyAlphabetStringConvertsToEmptyAlphabetArray()
    {
        $emptyString = '';
        $alphabet = $this->utils->toArrayFromStr($emptyString);
        $this->assertEmpty($alphabet, 'Result array is not empty!');
    }

    public function testNonEmptyAlphabetStringSplitsByLetterIntoAlphabetArray()
    {

        $nonEmptyString = 'ABCD';
        $alphabet = $this->utils->toArrayFromStr($nonEmptyString);
        $this->assertEquals(['A', 'B', 'C', 'D'], $alphabet);
    }

    public function testUnicodeAlphabetStringSplitsByLetterIntoAlphabetArray()
    {
        $unicodeString = 'AßüÊÀ';
        $alphabet = $this->utils->toArrayFromStr($unicodeString);
        $this->assertEquals(['A', 'ß', 'ü', 'Ê', 'À'], $alphabet);
    }
}
<?php

namespace tests\main;

use Generator;
use main\Dictionary;
use main\utils\CsvReader;
use PHPUnit\Framework\TestCase;
use SplFixedArray;

class DictionaryTest extends TestCase
{
    public function testGetDictSizeWhenNotSet()
    {
        $dictionary = $this->createDictionaryWithDictSizeEquals3();
        $dictSize = $dictionary->getDictSize();
        self::assertEquals(3, $dictSize);
    }

    public function testGetDictSizeWhenAlreadySet()
    {
        $dictionary = $this->createDictionaryWithDictSizeEquals3();

        $setPrivateDictSize = $dictionary->getDictSize();
        $getDictSizeFromPrivate = $dictionary->getDictSize();

        self::assertEquals(3, $getDictSizeFromPrivate);
    }

    public function testGetContentWhenNotSet()
    {
        $dictionary = $this->createDictionaryWithDictSizeEquals3AndContent();
        $actualContent = $dictionary->getContent();
        $this->assertEquals(
            SplFixedArray::fromArray(['some', 'dict', 'words', null]),
            $actualContent
        );
    }

    public function testGetContentWhenAlreadySet()
    {
        $dictionary = $this->createDictionaryWithDictSizeEquals3AndContent();

        $setPrivateContent = $dictionary->getContent();
        $getContentFromPrivate = $dictionary->getContent();

        $this->assertEquals(
            SplFixedArray::fromArray(['some', 'dict', 'words', null]),
            $getContentFromPrivate
        );
    }

    private function generate(array $yieldValues): Generator
    {
        yield from $yieldValues;
    }

    private function createCsvReaderMockWithAmountOfRowsEquals3()
    {
        $csvReaderMock = $this->createMock(CsvReader::class);
        $csvReaderMock->expects($this->once())
            ->method('getAmountOfRows')
            ->willReturn(3);
        return $csvReaderMock;
    }

    private function createDictionaryWithDictSizeEquals3(): Dictionary
    {
        $csvReaderMock = $this->createCsvReaderMockWithAmountOfRowsEquals3();
        return new Dictionary($csvReaderMock);
    }

    private function createDictionaryWithDictSizeEquals3AndContent(): Dictionary
    {
        $csvReaderMock = $this->createCsvReaderMockWithAmountOfRowsEquals3();
        $csvReaderMock->expects($this->once())
            ->method('rows')
            ->willReturn($this->generate(['some', 'dict', 'words']));
        return new Dictionary($csvReaderMock);
    }
}
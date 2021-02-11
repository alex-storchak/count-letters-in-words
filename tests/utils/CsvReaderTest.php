<?php

namespace tests\main\utils;

use main\utils\CsvReader;
use main\utils\FileClient;
use PHPUnit\Framework\TestCase;

class CsvReaderTest extends TestCase
{
    public function testRowsForNonEmptyFile()
    {
        $fileClientMock = $this->getFileClientMockForNonEmptyFile();
        $fileClientMock->expects($this->exactly(2))
            ->method('fgetcsv')
            ->willReturnOnConsecutiveCalls(['some'], ['staff']);
        $csvReader = new CsvReader($fileClientMock);

        $actual = $this->retrieveDataFromRowsGenerator($csvReader);

        $this->assertEquals(['SOME', 'STAFF'], $actual);
    }

    public function testRowsForEmptyFile()
    {
        $fileClientMock = $this->getFileClientMockForEmptyFile();
        $fileClientMock->expects($this->never())
            ->method('fgetcsv');

        $csvReader = new CsvReader($fileClientMock);

        $actual = $this->retrieveDataFromRowsGenerator($csvReader);

        $this->assertEquals([], $actual);
    }

    public function testGetAmountOfRowsForNonEmptyFile()
    {
        $fileClientMock = $this->getFileClientMockForNonEmptyFile();
        $fileClientMock->expects($this->exactly(2))
            ->method('fgets')
            ->willReturnOnConsecutiveCalls('some', 'staff');

        $csvReader = new CsvReader($fileClientMock);

        $actualAmountOfRows = $csvReader->getAmountOfRows();
        $this->assertEquals(2, $actualAmountOfRows);
    }

    public function testGetAmountOfRowsForEmptyFile()
    {
        $fileClientMock = $this->getFileClientMockForEmptyFile();
        $fileClientMock->expects($this->never())
            ->method('fgets');

        $csvReader = new CsvReader($fileClientMock);

        $actualAmountOfRows = $csvReader->getAmountOfRows();
        $this->assertEquals(0, $actualAmountOfRows);
    }

    /**
     * @return FileClient|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getFileClientMockForNonEmptyFile()
    {
        $fileClientMock = $this->createMock(FileClient::class);
        $fileClientMock->expects($this->once())
            ->method('rewind');
        $fileClientMock->expects($this->exactly(3))
            ->method('feof')
            ->willReturnOnConsecutiveCalls(false, false, true);
        return $fileClientMock;
    }

    /**
     * @return FileClient|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getFileClientMockForEmptyFile()
    {
        $fileClientMock = $this->createMock(FileClient::class);
        $fileClientMock->expects($this->once())
            ->method('rewind');
        $fileClientMock->expects($this->exactly(1))
            ->method('feof')
            ->willReturnOnConsecutiveCalls(true);
        return $fileClientMock;
    }

    private function retrieveDataFromRowsGenerator(CsvReader $csvReader): array
    {
        $actual = [];
        foreach ($csvReader->rows() as $row) {
            $actual[] = $row;
        }
        return $actual;
    }
}

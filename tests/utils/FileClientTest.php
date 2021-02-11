<?php

namespace tests\main\utils;

use main\utils\FileClient;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class FileClientTest extends TestCase
{
    const EXISTING_PATH = __DIR__ . '/nonEmptyData.txt';
    const EXISTING_SAVE_DATA_PATH = __DIR__ . '/saveData.txt';
    const NON_EXISTING_PATH = 'some/non-existing/path';

    public function testExistingFileOpened()
    {
        $fileClient = $this->getClientForExistingFile();
        $fileClient->open();
        $file = $fileClient->getFile();
        $this->assertNotFalse($file);
    }

    public function testNonExistingFileThrowsException()
    {
        $fileClient = $this->getClientForNonExistingFile();
        $this->expectException(RuntimeException::class);
        $fileClient->open();
    }

    public function testRewindFilePointer()
    {
        $fileClient = $this->getClientForExistingFile();
        $file = $this->getFileWithThePointerAtTheEndOfFile($fileClient);

        $fileClient->rewind();
        $dataFromTheFile = fgets($file, 4096);

        $this->assertNotFalse($dataFromTheFile, "File hasn't rewound.");
    }

    public function testFeofIsTrueWhenFilePointerIsAtTheEndOfFile()
    {
        $fileClient = $this->getClientForExistingFile();
        $this->setFilePointerToTheEndOfFile($fileClient);
        $isEof = $fileClient->feof();
        $this->assertTrue($isEof);
    }

    public function testFeofIsFalseWhenFilePointerIsAtTheBeginningOfFile()
    {
        $fileClient = $this->getClientForExistingFile();
        $this->setFilePointerToTheBeginningOfFile($fileClient);
        $isEof = $fileClient->feof();
        $this->assertFalse($isEof);
    }

    public function testFgetcsvConvertsRowFromFileToIndexedArray()
    {
        $fileClient = $this->getClientForExistingFile();
        $rowAsArray = $fileClient->fgetcsv();
        $this->assertEquals(['some'], $rowAsArray);
    }

    public function testFgetsConvertsRowFromFileToString()
    {
        $fileClient = $this->getClientForExistingFile();
        $rowString = $fileClient->fgets();
        $this->assertEquals("some\n", $rowString);
    }

    public function testFgetsReturnsFalseIfCannotReadFromFile()
    {
        $fileClient = $this->getClientForExistingFile();
        $this->setFilePointerToTheEndOfFile($fileClient);
        $nothingToRead = $fileClient->fgets();
        $this->assertFalse($nothingToRead);
    }

    public function testGetFileReturnsFileThatSetImplicit()
    {
        $expectedContent = file_get_contents(self::EXISTING_PATH);
        $fileClient = $this->getClientForExistingFile();

        $file = $fileClient->getFile();
        $actualContent = fread($file, 4096);

        $this->assertEquals($expectedContent, $actualContent);
    }

    public function testGetFileReturnsFileThatSetExplicit()
    {
        $expectedContent = file_get_contents(self::EXISTING_PATH);
        $fileClient = $this->getClientForExistingFile();
        $fileClient->open();

        $file = $fileClient->getFile();
        $actualContent = fread($file, 4096);

        $this->assertEquals($expectedContent, $actualContent);
    }

    public function testSaveDataToFile()
    {
        $expectedContent =  "some\ndata";
        $fileClient = $this->getClientForExistingFile(self::EXISTING_SAVE_DATA_PATH);

        $fileClient->saveDataToFile($expectedContent);
        $file = $fileClient->getFile();
        $actualContent = fread($file, 4096);

        $this->assertEquals($expectedContent, $actualContent);
    }

    private function getFileWithThePointerAtTheEndOfFile(FileClient $fileClient)
    {
        $this->setFilePointerToTheEndOfFile($fileClient);
        return $fileClient->getFile();
    }

    private function setFilePointerToTheEndOfFile(FileClient $fileClient)
    {
        fseek($fileClient->getFile(), 0, SEEK_END);
        fgetc($fileClient->getFile()); // feof() !== true when the logical file pointer is an EOF (https://www.php.net/manual/ru/function.feof.php#67261)
    }

    private function setFilePointerToTheBeginningOfFile(FileClient $fileClient)
    {
        $fileClient->open();
    }

    private function getClientForExistingFile(string $filePath = self::EXISTING_PATH): FileClient
    {
        return $this->createFileClient($filePath);
    }

    private function getClientForNonExistingFile(): FileClient
    {
        return $this->createFileClient(self::NON_EXISTING_PATH);
    }

    private function createFileClient(string $filePath): FileClient
    {
        return new FileClient($filePath);
    }
}
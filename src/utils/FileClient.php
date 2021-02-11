<?php

namespace main\utils;

use RuntimeException;

class FileClient implements FileClientInterface
{
    /**
     * @var resource pointer to a file
     */
    private $file;
    /**
     * @var string absolute path to the file
     */
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function open(string $mode = self::READ_ONLY)
    {
        if (! $this->file = @fopen($this->filePath, $mode)) {
            throw new RuntimeException("Cannot open a file - {$this->filePath}");
        }
    }

    public function rewind()
    {
        $this->openIfNotSet();
        rewind($this->file);
    }

    public function feof(): bool
    {
        $this->openIfNotSet();
        return feof($this->file);
    }

    public function fgetcsv()
    {
        $this->openIfNotSet();
        return fgetcsv($this->file, 4096);
    }

    public function fgets()
    {
        $this->openIfNotSet();
        return fgets($this->file, 4096);
    }

    public function getFile()
    {
        $this->openIfNotSet();
        return $this->file;
    }

    public function saveDataToFile(string $data)
    {
        file_put_contents($this->filePath, $data);
    }

    private function openIfNotSet()
    {
        if (!isset($this->file)) {
            $this->open();
        }
    }
}
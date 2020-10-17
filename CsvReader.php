<?php

class CsvReader
{
    protected $file;

    public function __construct($filePath)
    {
        $this->file = fopen($filePath, 'r');
    }

    public function rows()
    {
        while (!feof($this->file)) {
            $row = fgetcsv($this->file, 4096);
            $row = iconv('cp1251', 'utf-8', strtoupper($row[0]));
            yield $row;
        }

        return;
    }
}
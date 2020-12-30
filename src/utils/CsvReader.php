<?php

namespace main\utils;

use Generator;

class CsvReader
{
    protected $file;

    public function __construct($filePath)
    {
        $this->file = fopen($filePath, 'r');
    }

    public function rows(): Generator
    {
        rewind($this->file);
        while (!feof($this->file)) {
            $row = fgetcsv($this->file, 4096);
            if (is_array($row)) {
                $row = iconv('cp1251', 'utf-8', strtoupper($row[0]));
            }
            yield $row;
        }
        return;
    }

    public function getAmountOfRows(): int
    {
        rewind($this->file);
        $count = 0;
        while (!feof($this->file)) {
            if (fgets($this->file, 4096)) {
                $count++;
            }
        }
        return $count;
    }
}
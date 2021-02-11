<?php

namespace main\utils;

use Generator;

class CsvReader
{
    /**
     * @var FileClientInterface provide interface for working with a file
     */
    protected FileClientInterface $fileClient;

    public function __construct(FileClientInterface $fileClient)
    {
        $this->fileClient = $fileClient;
    }

    public function rows(): Generator
    {
        $this->fileClient->rewind();
        while (! $this->fileClient->feof()) {
            $row = $this->fileClient->fgetcsv();
            if (is_array($row)) {
                $row = iconv('cp1251', 'utf-8', strtoupper($row[0]));
            }
            yield $row;
        }
        return;
    }

    public function getAmountOfRows(): int
    {
        $this->fileClient->rewind();
        $count = 0;
        while (! $this->fileClient->feof()) {
            if ($this->fileClient->fgets()) {
                $count++;
            }
        }
        return $count;
    }
}
<?php

namespace main;

use main\utils\CsvReader;
use SplFixedArray;

class Dictionary
{
    const STORE_DIR_PATH = __DIR__ . '/../data/dict/';

    private string $fileName;
    private CsvReader $csvReader;
    private int $dictSize;
    private SplFixedArray $content;

    /**
     * Dictionary constructor.
     * @param $fileName
     * @param CsvReader $csvReader
     */
    public function __construct(string $fileName, CsvReader $csvReader)
    {
        $this->fileName = $fileName;
        $this->csvReader = $csvReader;
    }

    public function getDictSize(): int
    {
        if (!isset($this->dictSize)) {
            $this->dictSize = $this->csvReader->getAmountOfRows();
        }
        return $this->dictSize;
    }

    public function getContent(): SplFixedArray
    {
        if (!isset($this->content)) {
            ini_set('memory_limit', '512M');
            $this->content = new SplFixedArray($this->getDictSize() + 1);
            $i = 0;
            foreach ($this->csvReader->rows() as $row) {
                $this->content[$i] = $row;
                $i++;
            }
        }
        return $this->content;
    }
}
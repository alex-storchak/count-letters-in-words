<?php

namespace main;

use main\utils\CsvReader;
use SplFixedArray;

class Dictionary
{
    const STORE_DIR_PATH = __DIR__ . '/../data/dict/';
    const INI_SET_MEMORY_LIMIT = '512M';

    private CsvReader $csvReader;
    private int $dictSize;
    private SplFixedArray $content;

    /**
     * Dictionary constructor.
     * @param CsvReader $csvReader
     */
    public function __construct(CsvReader $csvReader)
    {
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
            ini_set('memory_limit', self::INI_SET_MEMORY_LIMIT);
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
<?php

class CsvReader
{
    protected $file;

    public function __construct($filePath) {
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

function getAlphabet($alphabetStr): array
{
    $alphabet = [];
    foreach (preg_split('//u', $alphabetStr, -1, PREG_SPLIT_NO_EMPTY) as $char){
        $alphabet[] = $char;
    }
    return $alphabet;
}

function process($alphabetStr, $dictSize, $dictFile, $resultFile)
{
    $letters = getAlphabet($alphabetStr);
    $dict = new SplFixedArray($dictSize);
    $csv = new CsvReader($dictFile);
    $result = [];

    $i = 0;
    foreach ($csv->rows() as $row) {
        $dict[$i] = $row;
        $i++;
    }

    foreach ($letters as $letter) {
        $result[$letter] = [
            'count' => 0,
            'words' => [],
        ];

        foreach ($dict as $word) {
            if (strlen($word) > 15) // ТЗ: слова содержат не более 15 букв
                continue;

            $letterCount = substr_count($word, $letter);
            if ($letterCount == $result[$letter]['count']) {
                $result[$letter]['words'][] = $word;
            } elseif ($letterCount > $result[$letter]['count']) {
                $result[$letter]['count'] = $letterCount;
                $result[$letter]['words'] = [$word];
            }
        }
    }

    ob_start();
    print_r($result);
    $r = ob_get_clean();

    file_put_contents($resultFile, $r);
}


process('АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ', 1700000, 'russian.txt', 'resultRU.txt');
process('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 470000, 'english.txt', 'resultEN.txt');

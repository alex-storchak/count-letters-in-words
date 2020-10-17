<?php

require_once 'AlphabetUtils.php';
require_once 'CsvReader.php';

/**
 * Class LetterCounter
 */
class LetterCounter
{
    /**
     * Max amount of example words in result array
     * @var int
     */
    private $maxWordCount = 100;

    /**
     * @param int $maxWordCount
     */
    public function __construct($maxWordCount)
    {
        $this->maxWordCount = $maxWordCount;
    }

    public function process($alphabetStr, $dictSize, $dictFile, $resultFile)
    {
        $letters = AlphabetUtils::toArrayFromStr($alphabetStr);
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
                if ($letterCount == $result[$letter]['count'] && count($result[$letter]['words']) < $this->maxWordCount) {
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
}
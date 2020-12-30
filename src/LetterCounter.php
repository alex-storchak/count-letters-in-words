<?php

namespace main;

use main\utils\AlphabetUtils;
use main\utils\CsvReader;

/**
 * Class LetterCounter
 */
class LetterCounter
{
    /**
     * Max amount of example words in result array
     */
    private int $maxWordCount;
    /**
     * Processed result
     */
    private array $result = [];

    /**
     * @param int $maxWordCount
     */
    public function __construct(int $maxWordCount)
    {
        $this->maxWordCount = $maxWordCount;
    }

    public function process($alphabetStr, $dictFileName)
    {
        $dict = new Dictionary($dictFileName, new CsvReader(Dictionary::STORE_DIR_PATH . $dictFileName));
        $letters = AlphabetUtils::toArrayFromStr($alphabetStr);
        $result = [];

        foreach ($letters as $letter) {
            $result[$letter] = [
                'count' => 0,
                'words' => [],
            ];

            foreach ($dict->getContent() as $word) {
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

        $this->result = $result;
        $this->saveResult($dictFileName);
    }

    private function saveResult($dictFileName)
    {
        ob_start();
        print_r($this->result);
        $r = ob_get_clean();

        file_put_contents(Dictionary::STORE_DIR_PATH . '../result/result_' . $dictFileName, $r);
    }
}
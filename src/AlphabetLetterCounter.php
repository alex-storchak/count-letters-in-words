<?php

namespace main;

use main\utils\AlphabetUtils;

/**
 * Class LetterCounter
 */
class AlphabetLetterCounter
{
    const MAX_AMOUNT_LETTERS_IN_WORD = 15; // ТЗ: слова содержат не более 15 букв
    /**
     * Max amount of example words in result array
     */
    private int $maxWordCount;
    /**
     * String that contains all alphabet letters without spaces
     */
    private string $alphabetStr;
    /**
     * Contains dictionary content from file as object
     */
    private Dictionary $dictionary;

    public function __construct(
        int $maxWordCount,
        string $alphabetStr,
        Dictionary $dictionary
    ) {
        $this->maxWordCount = $maxWordCount;
        $this->alphabetStr = $alphabetStr;
        $this->dictionary = $dictionary;
    }

    public function process(): array
    {
        $dict = $this->dictionary;
        $letters = AlphabetUtils::toArrayFromStr($this->alphabetStr);
        $result = [];

        foreach ($letters as $letter) {
            $result[$letter] = [
                'count' => 0,
                'words' => [],
            ];

            foreach ($dict->getContent() as $word) {
                if (strlen($word) > self::MAX_AMOUNT_LETTERS_IN_WORD)
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

        return $result;
    }
}
<?php

use main\AlphabetLetterCounter;
use main\Dictionary;
use main\utils\CsvReader;
use main\utils\FileClient;

require 'vendor/autoload.php';

if ($argc < 3) {
    echo 'ERROR! Not enough arguments: ' .  PHP_EOL .
        '   - First should be the a dictionary file name inside the dir /data/dict' . PHP_EOL .
        '   - Second should be the alphabet string of the corresponding language' .
        ' (ex. "ABCDEFGHIJKLMNOPQRSTUVWXYZ")' . PHP_EOL .
        '   - [Optional] Third should be the max amount of example matched words for each letter' . PHP_EOL;
    exit(1);
}

$dictFileName = $argv[1]; // 'english.txt'
$alphabetStr = $argv[2]; // 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
$maxWordCount = ($argc == 4) ? $argv[3] : 100;

$dictFilePath = Dictionary::STORE_DIR_PATH . $dictFileName;
$resultFilePath = Dictionary::STORE_DIR_PATH . '../result/result_' . $dictFileName;

$letterCounter = new AlphabetLetterCounter(
    $maxWordCount,
    $alphabetStr,
    new Dictionary(new CsvReader(new FileClient($dictFilePath)))
);
$result = $letterCounter->process();
(new FileClient($resultFilePath))->saveDataToFile(
    print_r($result, true)
);

exit(0);
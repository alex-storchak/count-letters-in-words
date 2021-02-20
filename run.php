<?php

use main\AlphabetLetterCounter;
use main\Dictionary;

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
$maxWordCount = ($argc == 4) ? (int) $argv[3] : 100;

// Configuring DI container
$container = require __DIR__ . '/app/bootstrap.php';
$container->set('dictFilePath', Dictionary::STORE_DIR_PATH . $dictFileName);
$container->set('resultFilePath', Dictionary::STORE_DIR_PATH . '../result/result_' . $dictFileName);
$container->set('maxWordCount', $maxWordCount);
$container->set('alphabetStr', $alphabetStr);

// process
$letterCounter = $container->get(AlphabetLetterCounter::class);
$result = $letterCounter->process();

$resultFileClient = $container->get('resultFileClient');
$resultFileClient->saveDataToFile(print_r($result, true));

exit(0);
<?php

use main\AlphabetLetterCounter;
use main\Dictionary;
use main\utils\CsvReader;
use main\utils\FileClient;
use main\utils\FileClientInterface;

use function DI\autowire;
use function DI\create;
use function DI\get;

return [
    'maxWordCount' => 100,
    'alphabetStr' => '',
    'dictFilePath' => '',
    'resultFilePath' => '',
    CsvReader::class => autowire(),
    Dictionary::class => autowire(),
    FileClientInterface::class => get('dictFileClient'),
    'dictFileClient' => create(FileClient::class)
        ->constructor(get('dictFilePath')),
    'resultFileClient' => create(FileClient::class)
        ->constructor(get('resultFilePath')),
    AlphabetLetterCounter::class => autowire()
        ->constructorParameter('maxWordCount', get('maxWordCount'))
        ->constructorParameter('alphabetStr', get('alphabetStr')),
];
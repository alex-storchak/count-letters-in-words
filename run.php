<?php

use main\LetterCounter;

require 'vendor/autoload.php';

$maxWordCount = ($argc == 2) ? $argv[1] : 100;

$letterCounter = new LetterCounter($maxWordCount);

// uncomment for run script
// $letterCounter->process(<string with an alphabet of the chosen language>, file name with extension for dictionary from /data/dict );

// examples
//$letterCounter->process('АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ', 'russian.txt');
//$letterCounter->process('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'english.txt');
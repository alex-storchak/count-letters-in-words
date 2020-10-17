<?php

require_once 'LetterCounter.php';

$maxWordCount = ($argc == 2) ? $argv[1] : 100;

$letterCounter = new LetterCounter($maxWordCount);

$letterCounter->process('АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ', 1700000, 'russian.txt', 'resultRU.txt');
$letterCounter->process('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 470000, 'english.txt', 'resultEN.txt');
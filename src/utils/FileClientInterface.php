<?php

namespace main\utils;

interface FileClientInterface
{
    const READ_ONLY = 'r';

    public function open(string $mode);
    public function rewind();
    public function feof(): bool;
    public function fgetcsv();
    public function fgets();
    public function getFile();
    public function saveDataToFile(string $data);
}
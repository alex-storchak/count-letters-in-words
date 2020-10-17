<?php

class AlphabetUtils
{
    public static function toArrayFromStr($alphabetStr): array
    {
        $alphabet = [];
        foreach (preg_split('//u', $alphabetStr, -1, PREG_SPLIT_NO_EMPTY) as $char) {
            $alphabet[] = $char;
        }
        return $alphabet;
    }
}
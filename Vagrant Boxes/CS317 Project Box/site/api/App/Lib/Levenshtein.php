<?php

namespace App\Lib;

class Levenshtein
{
//    https://stackoverflow.com/questions/26271656/levenshtein-distance-on-diacritic-characters
    public static function levenshtein_utf8($string1,$string2) : int
    {
        $len_str1 = mb_strlen($string1,'UTF-8');
        $len_str2 = mb_strlen($string2,'UTF-8');
        if ($len_str1 < $len_str2) {return self::levenshtein_utf8($string2,$string1);}
        if ($len_str1 === 0) {return $len_str2;}
        if ($string1 === $string2) {return 0;}
        $previousRow = range(0,$len_str2);
        if (!str_contains($string1, $string2)) {return 255;}
        $currentRow = array();
        for ($i = 0; $i < $len_str1; $i++)
        {
            $currentRow = array();
            $currentRow[0] = $i + 1;
            $char1 = mb_substr($string1,$i,1,'UTF-8');
            for ($j = 0; $j < $len_str2; $j++)
            {
                $char2 = mb_substr($string2,$j,1,'UTF-8');
                $insertions = $previousRow[$j+1] + 1;
                $deletions = $currentRow[$j] + 1;
                $substitutions = $previousRow[$j] + (($char1 != $char2) ? 1:0);
                $currentRow[] = min($insertions,$deletions,$substitutions);
            }
            $previousRow = $currentRow;
        }
        return $previousRow[$len_str2];
    }

}

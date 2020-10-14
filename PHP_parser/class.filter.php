<?php

class Filter{
    private $r1;
    private $r2;
    private $result; 
 
    function countDigits($text)
    {
        $r1 = preg_match_all( "/[0-9]/", $text); // ile liczb pomiędzy <p>
        $r2 = str_word_count($text); // ile słów pomiędzy <p>
        $r2 += $r1;
        if($r2 > 0){
            $result = $r1/$r2 * 100;
            return $result; 
        }else{
            return 1;
        }
    }
}

?> 
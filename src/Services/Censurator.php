<?php

namespace App\Services;

class Censurator
{

    const MOTS_INTERDITS = ['caca', 'pipi'];


    function purify($phrase): string
    {
        // Avec le bon nombre d'étoiles
        foreach (self::MOTS_INTERDITS as $mot) {
            $phrase = str_replace($mot, $mot[0].str_repeat("*", mb_strlen($mot) - 2).$mot[-1], $phrase);
        }
//        $phrase = str_ireplace(self::MOTS_INTERDITS, '***', $phrase); // Sans le nbre d'étoiles
        return $phrase;
    }
}
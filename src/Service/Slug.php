<?php

namespace App\Service;

/**
 * Class Slug
 * @package App\Service
 */
class Slug
{
    /**
     * @param $txt
     * @return array|false|string|string[]|null
     */
    public function slugify($txt)
    {
        /* Get rid of accented characters */
        $search = explode(",", "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
        $replace = explode(",", "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
        $txt = str_replace($search, $replace, $txt);

        /* Lowercase all the characters */
        $txt = strtolower($txt);

        /* Avoid whitespace at the beginning and the ending */
        $txt = trim($txt);

        /* Replace all the characters that are not in a-z or 0-9 by a hyphen */
        $txt = preg_replace("/[^a-z0-9]/", "-", $txt);
        /* Remove hyphen anywhere it's more than one */
        $txt = preg_replace("/[\-]+/", '-', $txt);
        if (substr($txt, -1) === '-') {
            return substr($txt, 0, -1);
        }
        return $txt;
    }
}
<?php

namespace Ci\Oxid\FormBuilder\Core;

class StringUtil
{
    /**
     * Standardize a parameter (strip special characters and convert spaces)
     *
     * @param string  $strString            The input string
     * @param boolean $blnPreserveUppercase True to preserver uppercase characters
     *
     * @return string The converted string
     */
    public static function standardize($strString, $blnPreserveUppercase = false)
    {
        $arrSearch = array('/[^\pN\pL \.\&\/_-]+/u', '/[ \.\&\/-]+/');
        $arrReplace = array('', '-');

        $strString = html_entity_decode($strString, ENT_QUOTES, 'utf-8');
        // $strString = static::stripInsertTags($strString);
        $strString = preg_replace($arrSearch, $arrReplace, $strString);

        if (is_numeric(substr($strString, 0, 1))) {
            $strString = 'id-' . $strString;
        }

        if (!$blnPreserveUppercase) {
            $strString = mb_strtolower($strString);
        }

        return trim($strString, '-');
    }
}

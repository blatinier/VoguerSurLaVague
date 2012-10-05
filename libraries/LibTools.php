<?php
class LibTools {
    /*
     * Sanitize string for filename
     * @return string
     */
    public static function sanitize_string($s, $glue = '-') {
        // Lower case
        $s = strtolower($s);
        // Replaces accentuated chars by their non-accentuated version and spaces by "-"
        $s = strtr($s, utf8_decode('@ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËéèêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ'),
                       utf8_decode('aaaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn'));
        // Replaces other chars by "-"
        $s = preg_replace('#([^a-z0-9'.$glue.'])#', $glue, $s);
        // Remove consecutives "-"
        $s = preg_replace('#(['.$glue.']+)#', $glue, $s);
        // Trim glue
        $s = trim($s, $glue);
        return $s;
    }
}

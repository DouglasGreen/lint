<?php

namespace Lint;

/** Static text functions. */
class CssText
{
    /**
     * Is this identifier camel case?
     *
     * @param string $ident
     *
     * @return bool
     */
    public static function isCamelCase($ident)
    {
        if (preg_match('/[_-]|[A-Z]{2,}|^[A-Z]/', $ident)) {
            return false;
        }
        return true;
    }

    /**
     * Split a selector into words.
     *
     * @param string $selector
     *
     * @return array
     */
    public static function splitSelector($selector)
    {
        if (preg_match_all('/-?[_a-zA-Z]+[_a-zA-Z0-9-]*/', $selector, $matches)) {
            return $matches[0];
        }
        return [];
    }
}

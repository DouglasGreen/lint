<?php

namespace Lint;

/** Basic text functions */
class Text
{
    /**
     * Get the line number of a snippet inside a larger text.
     *
     * @param string $text
     * @param string $snippet
     *
     * @return int
     */
    public function getLineNumber($text, $snippet)
    {
        $pos = strpos($text, $snippet);
        $intro = substr($text, 0, $pos);
        $index = substr_count($intro, "\n");
        $lineNum = $index + 1;
        return $lineNum;
    }
}

<?php

namespace Lint;

/** Base checker class for PHP */
class PhpChecker extends Checker
{
    /**
     * Print a token error message while retrieving its line of code.
     *
     * @param string $message
     * @param array $token
     */
    protected function printTokenError($message, array $token)
    {
        $allLines = $this->parser->getAllLines();
        $lineNum = $token[2];
        $index = $lineNum - 1;
        $line = $allLines[$index];
        $this->printError($message, $line, $lineNum);
    }
}

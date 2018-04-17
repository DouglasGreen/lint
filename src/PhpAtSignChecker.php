<?php

namespace Lint;

/** Check at sign. */
class PhpAtSignChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getTokens();
        foreach ($tokens as $index => $token) {
            if (is_string($token) && $token == '@') {
                $nextToken = $tokens[$index + 1];
                $this->printError('Do not use @ sign to suppress errors', '', $nextToken[2]);
            }
        }
    }
}

<?php

namespace Lint;

/** Check end braces for comments. */
class PhpCommentedBraceChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getAllTokens();
        $index = 0;
        $count = count($tokens);
        while ($index < $count) {
            $token = $tokens[$index];
            if (is_string($token) && $token == '}') {
                while ($index < $count - 1) {
                    $index++;
                    $token = $tokens[$index];
                    if (PhpToken::isWhitespace($token) && strpos($token[1], "\n") === false) {
                        continue;
                    }
                    if (PhpToken::isComment($token)) {
                        $this->printError('Don\'t put comments after ending braces', $token[1], $token[2]);
                    }
                    break;
                }
            }
            $index++;
        }
    }
}

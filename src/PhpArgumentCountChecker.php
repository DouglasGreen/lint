<?php

namespace Lint;

/** Check function argument count. */
class PhpArgumentCountChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getTokens();
        $count = count($tokens);
        $index = 0;
        while ($index < $count) {
            $token = $tokens[$index];
            $funcToken = null;
            if (is_array($token) && $token[0] == T_FUNCTION) {
                $argCount = 1;
                while ($index < $count) {
                    $index++;
                    $token = $tokens[$index];
                    if (is_array($token)) {
                        if (!$funcToken && $token[0] == T_STRING) {
                            $funcToken = $token;
                        }
                    } else {
                        if ($token == ',') {
                            $argCount++;
                        } elseif ($token == '{') {
                            break;
                        }
                    }
                }
                if ($argCount > 4) {
                    $message = sprintf('Limit functions to 4 arguments or less (%s has %d arguments)', $funcToken[1], $argCount);
                    $this->printTokenError($message, $funcToken);
                }
            }
            $index++;
        }
    }
}

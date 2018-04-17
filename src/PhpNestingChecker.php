<?php

namespace Lint;

/** Check nesting of brackets, braces, and parentheses. */
class PhpNestingChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getTokens();
        $braceCount = 0;
        $bracketCount = 0;
        $parenCount = 0;
        foreach ($tokens as $token) {
            if (is_array($token)) {
                $lineNum = $token[2];
            } else {
                switch ($token) {
                    case '[':
                        $bracketCount++;
                        break;
                    case '(':
                        $parenCount++;
                        break;
                    case '{':
                        $braceCount++;
                        break;
                    case ']':
                        if ($bracketCount > 3) {
                            $this->printError('Avoid excessive nesting of brackets', '', $lineNum);
                            $bracketCount = 0;
                        } elseif ($bracketCount > 0) {
                            $bracketCount--;
                        }
                        break;
                    case ')':
                        if ($parenCount > 3) {
                            $this->printError('Avoid excessive nesting of parentheses', '', $lineNum);
                            $parenCount = 0;
                        } elseif ($parenCount > 0) {
                            $parenCount--;
                        }
                        break;
                    case '}':
                        if ($braceCount > 5) {
                            $this->printError('Avoid excessive nesting of braces', '', $lineNum);
                            $braceCount = 0;
                        } elseif ($braceCount > 0) {
                            $braceCount--;
                        }
                        break;
                }
            }
        }
    }
}

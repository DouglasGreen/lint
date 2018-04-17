<?php

namespace Lint;

/** Check logical operators */
class PhpLogicalOperatorChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getTokens();
        foreach ($tokens as $token) {
            if (is_array($token)) {
                $error = null;
                switch ($token[0]) {
                    case T_LOGICAL_AND:
                        $error = 'Use && instead of and';
                        break;
                    case T_LOGICAL_OR:
                        $error = 'Use || instead of or';
                        break;
                }
                if ($error) {
                    $error .= ' to avoid precedence issues';
                    $this->printError($error, '', $token[2]);
                }
            }
        }
    }
}

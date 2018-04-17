<?php

namespace Lint;

/** Check for magic numbers. */
class PhpNumberChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $source = $this->parser->getSource();
        $consts = [];

        // Get list of defined constants.
        if (preg_match_all('/\\bconst\\s+\\w+\\s*=\\s*(.*?);/', $source, $matches)) {
            foreach ($matches[1] as $value) {
                $consts[] = trim($value);
            }
        }

        // Get numbers and compare against constants.
        $tokens = $this->parser->getTokens();
        foreach ($tokens as $token) {
            if (is_array($token)) {
                $isLongNumber = $token[0] == T_LNUMBER && strlen($token[1]) >= 3;
                $isConst = in_array($token[1], $consts);
                if ($isLongNumber && !$isConst) {
                    $this->printError('Define magic numbers as constants', $token[1], $token[2]);
                }
            }
        }
    }
}

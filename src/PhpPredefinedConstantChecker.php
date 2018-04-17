<?php

namespace Lint;

/** Check predefined constant case. */
class PhpPredefinedConstantChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getTokens();
        foreach ($tokens as $token) {
            if (PhpToken::isPredefinedConstant($token)) {
                $keyword = $token[1];
                if ($keyword != strtoupper($keyword)) {
                    $this->printTokenError('Must uppercase predefined constants', $token);
                }
            }
        }
    }
}

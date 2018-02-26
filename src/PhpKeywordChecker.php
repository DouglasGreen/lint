<?php
namespace Lint;

/** Check keyword case. */
class PhpKeywordChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getTokens();
        foreach ($tokens as $token) {
            if (PhpToken::isKeyword($token)) {
                $keyword = $token[1];
                if ($keyword != strtolower($keyword)) {
                    $this->printTokenError('Must lowercase keywords', $token);
                }
            }
        }
    }
}

<?php
namespace Lint;

/** Check instanceof. */
class PhpInstanceOfChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getTokens();
        foreach ($tokens as $token) {
            if ($token[0] == T_INSTANCEOF) {
                $this->printTokenError('Avoid using instanceof for type checking', $token);
            }
        }
    }
}

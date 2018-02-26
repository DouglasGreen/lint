<?php
namespace Lint;

/** Check for commented source code. */
class PhpCommentedCodeChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $comments = $this->parser->getComments();
        foreach ($comments as $token) {
            if (preg_match('~\\$\\w+\\s*=.*;~', $token[1])) {
                $this->printError(
                    'Remove unused source code rather than commenting it out',
                    $token[1],
                    $token[2]
                );
            }
        }
    }
}

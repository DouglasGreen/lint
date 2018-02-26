<?php
namespace Lint;

/** Check todos. */
class PhpToDoChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $comments = $this->parser->getComments();
        foreach ($comments as $token) {
            if (preg_match('/\\b(to-?do|fix\\s*me|@hack)\\b/i', $token[1])) {
                $this->printError('Todo comment left in code', $token[1], $token[2]);
            }
        }
    }
}

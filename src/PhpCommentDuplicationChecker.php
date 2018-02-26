<?php
namespace Lint;

/** Check for duplicate comments. */
class PhpCommentDuplicationChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $comments = $this->parser->getComments();
        $lookups = [];
        foreach ($comments as $token) {
            $comment = $token[1];
            $lineNum = $token[2];
            if (isset($lookups[$comment])) {
                $this->printError('Duplicate comment', $comment, $lineNum);
            }
            $lookups[$comment] = true;
        }
    }
}

<?php
namespace Lint;

/** Check for eval statements. */
class PhpEvalChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/\\b(eval)\\s*\\(/', $line)) {
                $this->printError('Remove eval statement', $line, $index + 1);
            }
        }
    }
}

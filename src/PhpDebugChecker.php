<?php
namespace Lint;

/** Check for debug statements. */
class PhpDebugChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/\\b(debug_|print_r|var_dump|var_export)\\s*\\(/', $line)) {
                $this->printError('Remove debug statements', $line, $index + 1);
            }
        }
    }
}

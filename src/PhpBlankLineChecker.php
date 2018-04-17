<?php

namespace Lint;

/** Check attribute access. */
class PhpBlankLineChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getAllLines();
        $blankCount = 0;
        foreach ($lines as $line) {
            if (!trim($line)) {
                $blankCount++;
            }
        }
        $percent = round($blankCount / count($lines) * 100);
        if ($percent >= 20) {
            $message = sprintf('Percent of blank lines is %d%% and should be less than 20%%', $percent);
            $this->printError($message);
        }
    }
}

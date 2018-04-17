<?php

namespace Lint;

/** Check switch for break */
class PhpSwitchChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/^\\s*case\\b.*:/i', $line)) {
                $prevLine = $lines[$index - 1];
                if (!preg_match('/^\\s*(switch\\s*\\(|case\\b.*:|break\\s*;)/', $prevLine)) {
                    $this->printError('Case statement with no previous break', $line, $index + 1);
                }
            }
        }
    }
}

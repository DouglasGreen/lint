<?php

namespace Lint;

/** Check that functions are in classes. */
class PhpFunctionClassChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            // Return at start of class definition.
            if (PhpText::isClassLine($line)) {
                return;
            }
            if (PhpText::isFunctionLine($line)) {
                $this->printError('All functions should be defined in classes', $line, $index + 1);
            }
        }
    }
}

<?php
namespace Lint;

/** Check for global keyword. */
class PhpGlobalChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/^\\s*global\\b/', $line)) {
                $this->printError('Do not use global', $line, $index + 1);
            }
        }
    }
}

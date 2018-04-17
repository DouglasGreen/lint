<?php

namespace Lint;

/** Check static functions for $this references. */
class PhpStaticChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        $isStatic = false;
        foreach ($lines as $index => $line) {
            if (PhpText::isFunctionLine($line)) {
                $isStatic = preg_match('/\\bstatic\\b/i', $line);
            } elseif ($isStatic && preg_match('/\\$this/', $line)) {
                $this->printError('Static functions should not have $this references', $line, $index + 1);
            }
            if (preg_match('/self::\\$\\w+\\s*=[^=]/i', $line)) {
                $this->printError('Avoid making assignments to static properties', $line, $index + 1);
            }
        }
    }
}

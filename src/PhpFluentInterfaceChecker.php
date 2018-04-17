<?php

namespace Lint;

/** Check for fluent interfaces. */
class PhpFluentInterfaceChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/^\\s*return\\s+$this\\s*;\\s*$/i', $line)) {
                $this->printError('Avoid fluent interfaces (method chaining)', $line, $index + 1);
            }
        }
    }
}

<?php

namespace Lint;

/** Check attribute access. */
class PhpAttributeAccessChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/\\b(public|private)\\b( \\w+)* \\$\\w+/', $line)) {
                $this->printError('All attributes should be protected', $line, $index + 1);
            }
        }
    }
}

<?php

namespace Lint;

/** Check method access. */
class PhpMethodAccessChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $funcLines = $this->parser->getFunctionLines();
        foreach ($funcLines as $index => $line) {
            if (preg_match('/private(\\s+\\w+)*\\s+function/', $line)) {
                $this->printError('Avoid functions with private access', $line, $index + 1);
            }
        }
    }
}

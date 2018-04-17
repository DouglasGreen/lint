<?php

namespace Lint;

/** Check that function access is specified. */
class PhpFunctionAccessChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $funcLines = $this->parser->getFunctionLines();
        foreach ($funcLines as $index => $line) {
            if (!preg_match('/\\b(public|protected|private)\\b.*\\bfunction\\b/', $line)) {
                $this->printError('No access level specified', $line, $index + 1);
            }
        }
    }
}
